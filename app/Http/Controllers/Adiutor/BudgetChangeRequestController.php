<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\BudgetChangeRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\AuditLog;
use App\Notifications\BudgetChangeRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetChangeRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role !== 'adiutor') {
                abort(403, 'Unauthorized access. Adiutors only.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the adiutor's budget change requests
     */
    public function index(Request $request)
    {
        $adiutorId = Auth::id();

        $query = BudgetChangeRequest::where('adiutor_id', $adiutorId)
            ->with(['task.project', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $budgetRequests = $query->paginate(10);

        // Get statistics for this adiutor
        $stats = [
            'pending' => BudgetChangeRequest::where('adiutor_id', $adiutorId)
                ->where('status', 'pending')->count(),
            'approved' => BudgetChangeRequest::where('adiutor_id', $adiutorId)
                ->where('status', 'approved')->count(),
            'rejected' => BudgetChangeRequest::where('adiutor_id', $adiutorId)
                ->where('status', 'rejected')->count(),
        ];

        return view('adiutor.budget-requests.index', compact('budgetRequests', 'stats'));
    }

    /**
     * Show the form for creating a new budget change request
     */
    public function create(Request $request)
    {
        $taskId = $request->get('task_id');
        $adiutorId = Auth::id();

        // If task_id is provided, pre-select that task
        $selectedTask = null;
        if ($taskId) {
            $selectedTask = Task::where('taskID', $taskId)
                ->where('assignedTo', $adiutorId)
                ->with('project')
                ->first();

            if (!$selectedTask) {
                return redirect()->route('adiutor.budget-requests.index')
                    ->with('error', 'Task not found or not assigned to you.');
            }

            // Check for existing pending request
            $existingRequest = BudgetChangeRequest::where('task_id', $taskId)
                ->where('adiutor_id', $adiutorId)
                ->pending()
                ->first();

            if ($existingRequest) {
                return redirect()->route('adiutor.budget-requests.show', $existingRequest->id)
                    ->with('info', 'You already have a pending request for this task.');
            }
        }

        // Get all tasks assigned to this adiutor (for dropdown)
        $availableTasks = Task::where('assignedTo', $adiutorId)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('adiutor.budget-requests.create', compact('selectedTask', 'availableTasks'));
    }

    /**
     * Store a newly created budget change request
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,taskID',
            'requested_budget' => 'required|numeric|min:0',
            'reason' => 'required|string|min:20|max:1000',
        ]);

        $adiutorId = Auth::id();
        $task = Task::where('taskID', $request->task_id)->firstOrFail();

        // Verify task is assigned to this adiutor
        if ($task->assignedTo != $adiutorId) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['task_id' => 'This task is not assigned to you.']);
        }

        // Validate requested budget is different from current
        if ($request->requested_budget == ($task->allocated_budget ?? 0)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['requested_budget' => 'Requested budget must be different from current budget.']);
        }

        // Check for existing pending request
        $existingRequest = BudgetChangeRequest::where('task_id', $request->task_id)
            ->where('adiutor_id', $adiutorId)
            ->pending()
            ->first();

        if ($existingRequest) {
            return redirect()->route('adiutor.budget-requests.show', $existingRequest->id)
                ->with('error', 'You already have a pending budget change request for this task.');
        }

        DB::beginTransaction();
        try {
            // Create budget change request
            $budgetRequest = BudgetChangeRequest::create([
                'task_id' => $request->task_id,
                'adiutor_id' => $adiutorId,
                'current_budget' => $task->allocated_budget ?? 0,
                'requested_budget' => $request->requested_budget,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            // Log sensitive action
            AuditLog::logSensitiveAction('budget_change_requested', [
                'task_id' => $request->task_id,
                'current_budget' => $task->allocated_budget ?? 0,
                'requested_budget' => $request->requested_budget,
                'difference' => $request->requested_budget - ($task->allocated_budget ?? 0),
                'reason' => $request->reason,
            ]);

            DB::commit();

            // Notify admins (outside transaction - non-critical)
            $this->notifyAdmins($budgetRequest, $task);

            return redirect()->route('adiutor.budget-requests.show', $budgetRequest->id)
                ->with('success', 'Budget change request submitted successfully. Awaiting admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create budget change request', [
                'task_id' => $request->task_id,
                'adiutor_id' => $adiutorId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to submit request. Please try again.']);
        }
    }

    /**
     * Display the specified budget change request
     */
    public function show($id)
    {
        $adiutorId = Auth::id();

        $budgetRequest = BudgetChangeRequest::where('id', $id)
            ->where('adiutor_id', $adiutorId)
            ->with(['task.project', 'reviewer'])
            ->firstOrFail();

        return view('adiutor.budget-requests.show', compact('budgetRequest'));
    }

    /**
     * Cancel a pending budget change request
     */
    public function cancel($id)
    {
        $adiutorId = Auth::id();

        $budgetRequest = BudgetChangeRequest::where('id', $id)
            ->where('adiutor_id', $adiutorId)
            ->pending()
            ->firstOrFail();

        $budgetRequest->delete();

        // Log the cancellation
        AuditLog::logSensitiveAction('budget_change_cancelled', [
            'request_id' => $id,
            'task_id' => $budgetRequest->task_id,
            'requested_budget' => $budgetRequest->requested_budget,
        ]);

        return redirect()->route('adiutor.budget-requests.index')
            ->with('success', 'Budget change request cancelled successfully.');
    }

    /**
     * Notify admins about the new budget change request
     *
     * @param BudgetChangeRequest $budgetRequest
     * @param Task $task
     */
    protected function notifyAdmins(BudgetChangeRequest $budgetRequest, Task $task): void
    {
        $admins = User::where('role', 'admin')->where('status', 'active')->get();

        // Eager load relationships for the notification
        $budgetRequest->load(['task', 'adiutor']);

        foreach ($admins as $admin) {
            try {
                $admin->notify(new BudgetChangeRequestNotification($budgetRequest));

                \Log::info('Budget change request notification sent to admin', [
                    'admin_id' => $admin->id,
                    'request_id' => $budgetRequest->id,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to notify admin about budget change request', [
                    'admin_id' => $admin->id,
                    'request_id' => $budgetRequest->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
