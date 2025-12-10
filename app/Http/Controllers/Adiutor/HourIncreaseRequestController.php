<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\HourIncreaseRequest;
use App\Models\ProjectAssignment;
use App\Models\Task;
use App\Models\User;
use App\Notifications\HourIncreaseRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HourIncreaseRequestController extends Controller
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
     * Display list of hour increase requests for the adiutor
     */
    public function index()
    {
        $adiutor = Auth::user();

        $requests = HourIncreaseRequest::forAdiutor($adiutor->id)
            ->with(['project', 'projectAssignment', 'task', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get assignments approaching max hours (80%+ utilized)
        $warningAssignments = ProjectAssignment::where('adiutor_id', $adiutor->id)
            ->whereNotNull('max_hours')
            ->where('max_hours', '>', 0)
            ->whereRaw('total_hours_logged >= (max_hours * 0.8)')
            ->with('project')
            ->get();

        // Get tasks approaching max hours (80%+ utilized)
        $warningTasks = Task::where('assignedTo', $adiutor->id)
            ->whereNotNull('max_hours')
            ->where('max_hours', '>', 0)
            ->whereRaw('total_billable_hours >= (max_hours * 0.8)')
            ->with('project')
            ->get();

        return view('adiutor.hour-requests.index', compact('requests', 'warningAssignments', 'warningTasks'));
    }

    /**
     * Show the form for creating a new request
     */
    public function create(Request $request)
    {
        $adiutor = Auth::user();
        $assignmentId = $request->get('assignment_id');
        $taskId = $request->get('task_id');

        // Handle task-level request
        if ($taskId) {
            $task = Task::where('taskID', $taskId)
                ->where('assignedTo', $adiutor->id)
                ->with('project')
                ->firstOrFail();

            // Check if there's already a pending request for this task
            $existingRequest = HourIncreaseRequest::where('task_id', $task->taskID)
                ->where('adiutor_id', $adiutor->id)
                ->pending()
                ->first();

            if ($existingRequest) {
                return redirect()->route('adiutor.hour-requests.index')
                    ->with('error', 'You already have a pending request for this task.');
            }

            return view('adiutor.hour-requests.create-task', compact('task'));
        }

        // Handle assignment-level request
        $assignment = ProjectAssignment::where('id', $assignmentId)
            ->where('adiutor_id', $adiutor->id)
            ->with('project')
            ->firstOrFail();

        // Check if there's already a pending request
        $existingRequest = HourIncreaseRequest::where('project_assignment_id', $assignment->id)
            ->where('adiutor_id', $adiutor->id)
            ->pending()
            ->first();

        if ($existingRequest) {
            return redirect()->route('adiutor.hour-requests.index')
                ->with('error', 'You already have a pending request for this assignment.');
        }

        return view('adiutor.hour-requests.create', compact('assignment'));
    }

    /**
     * Store a newly created request
     */
    public function store(Request $request)
    {
        $adiutor = Auth::user();

        // Handle task-level request
        if ($request->has('task_id')) {
            return $this->storeTaskRequest($request, $adiutor);
        }

        // Handle assignment-level request
        return $this->storeAssignmentRequest($request, $adiutor);
    }

    /**
     * Store a task-level hour increase request
     */
    protected function storeTaskRequest(Request $request, $adiutor)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,taskID',
            'requested_hours' => 'required|numeric|min:1',
            'reason' => 'required|string|min:20|max:1000',
        ]);

        $task = Task::where('taskID', $request->task_id)
            ->where('assignedTo', $adiutor->id)
            ->firstOrFail();

        // Validate requested hours is greater than current
        if ($request->requested_hours <= ($task->max_hours ?? 0)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['requested_hours' => 'Requested hours must be greater than current max hours.']);
        }

        // Check for existing pending request
        $existingRequest = HourIncreaseRequest::where('task_id', $task->taskID)
            ->where('adiutor_id', $adiutor->id)
            ->pending()
            ->first();

        if ($existingRequest) {
            return redirect()->route('adiutor.hour-requests.index')
                ->with('error', 'You already have a pending request for this task.');
        }

        DB::beginTransaction();
        try {
            $hourRequest = HourIncreaseRequest::createForTask(
                $task,
                $adiutor->id,
                $request->requested_hours,
                $request->reason
            );

            DB::commit();

            // Notify admins
            $this->notifyAdmins($hourRequest);

            return redirect()->route('adiutor.hour-requests.index')
                ->with('success', 'Hour increase request for task submitted successfully. You will be notified when it is reviewed.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create task hour increase request', [
                'task_id' => $task->taskID,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to submit request. Please try again.']);
        }
    }

    /**
     * Store an assignment-level hour increase request
     */
    protected function storeAssignmentRequest(Request $request, $adiutor)
    {
        $request->validate([
            'assignment_id' => 'required|exists:project_assignments,id',
            'requested_hours' => 'required|numeric|min:1',
            'reason' => 'required|string|min:20|max:1000',
        ]);

        $assignment = ProjectAssignment::where('id', $request->assignment_id)
            ->where('adiutor_id', $adiutor->id)
            ->firstOrFail();

        // Validate requested hours is greater than current
        if ($request->requested_hours <= ($assignment->max_hours ?? 0)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['requested_hours' => 'Requested hours must be greater than current max hours.']);
        }

        // Check for existing pending request
        $existingRequest = HourIncreaseRequest::where('project_assignment_id', $assignment->id)
            ->where('adiutor_id', $adiutor->id)
            ->pending()
            ->first();

        if ($existingRequest) {
            return redirect()->route('adiutor.hour-requests.index')
                ->with('error', 'You already have a pending request for this assignment.');
        }

        DB::beginTransaction();
        try {
            $hourRequest = HourIncreaseRequest::createForAssignment(
                $assignment,
                $request->requested_hours,
                $request->reason
            );

            DB::commit();

            // Notify admins
            $this->notifyAdmins($hourRequest);

            return redirect()->route('adiutor.hour-requests.index')
                ->with('success', 'Hour increase request submitted successfully. You will be notified when it is reviewed.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create hour increase request', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to submit request. Please try again.']);
        }
    }

    /**
     * Display a specific request
     */
    public function show($id)
    {
        $adiutor = Auth::user();

        $hourRequest = HourIncreaseRequest::where('id', $id)
            ->where('adiutor_id', $adiutor->id)
            ->with(['project', 'projectAssignment', 'task', 'reviewer'])
            ->firstOrFail();

        return view('adiutor.hour-requests.show', compact('hourRequest'));
    }

    /**
     * Cancel a pending request
     */
    public function cancel($id)
    {
        $adiutor = Auth::user();

        $hourRequest = HourIncreaseRequest::where('id', $id)
            ->where('adiutor_id', $adiutor->id)
            ->pending()
            ->firstOrFail();

        $hourRequest->delete();

        return redirect()->route('adiutor.hour-requests.index')
            ->with('success', 'Request cancelled successfully.');
    }

    /**
     * Notify admins about the new request
     */
    protected function notifyAdmins(HourIncreaseRequest $request)
    {
        $admins = User::where('role', 'admin')->where('status', 'active')->get();

        foreach ($admins as $admin) {
            try {
                $admin->notify(new HourIncreaseRequestNotification($request));
                
                \Log::info('Hour increase request notification sent to admin', [
                    'admin_id' => $admin->id,
                    'request_id' => $request->id,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to notify admin about hour increase request', [
                    'admin_id' => $admin->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
