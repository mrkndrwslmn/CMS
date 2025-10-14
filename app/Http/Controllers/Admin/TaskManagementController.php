<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['client', 'assignedUser', 'form']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('taskTitle', 'like', "%{$search}%")
                  ->orWhere('taskDescription', 'like', "%{$search}%")
                  ->orWhereHas('client', function($clientQuery) use ($search) {
                      $clientQuery->where('fullName', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        // Assignee filter
        if ($request->filled('assignee')) {
            $query->where('assignedTo', $request->assignee);
        }
        
        // Client filter
        if ($request->filled('client')) {
            $query->where('client_id', $request->client);
        }
        
        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        $tasks = $query->paginate(15)->withQueryString();
        
        // Get filter options
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $adiutors = User::where('role', 'adiutor')->orderBy('fullName')->get();
        
        // Get statistics
        $stats = [
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
        ];
        
        return view('admin.tasks.index', compact('tasks', 'clients', 'adiutors', 'stats'));
    }
    
    public function show($id)
    {
        $task = Task::with(['client', 'assignedUser', 'form', 'documents'])->findOrFail($id);
        
        // Get task history/activity log if available
        $activities = []; // This could be implemented with a separate Activity model
        
        // Get adiutors for assignment dropdown
        $activities['adiutors'] = User::where('role', 'adiutor')->orderBy('fullName')->get();
        
        return view('admin.tasks.show', compact('task', 'activities'));
    }
    
    public function create()
    {
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $adiutors = User::where('role', 'adiutor')->orderBy('fullName')->get();
        $forms = Form::all();
        
        return view('admin.tasks.create', compact('clients', 'adiutors', 'forms'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'taskTitle' => 'required|string|max:255',
            'taskDescription' => 'required|string',
            'client_id' => 'required|exists:users,id',
            'assignedTo' => 'nullable|exists:users,id',
            'formID' => 'nullable|exists:forms,formID',
            'service_request_id' => 'nullable|exists:service_requests,id',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date|after:today',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'allocated_budget' => 'nullable|numeric|min:0',
        ]);
        
        // Check budget allocation if service request is specified
        if ($request->service_request_id && $request->allocated_budget) {
            $serviceRequest = \App\Models\ServiceRequest::find($request->service_request_id);
            if ($serviceRequest && $serviceRequest->getRemainingBudget() < $request->allocated_budget) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['allocated_budget' => 'Allocated budget exceeds remaining project budget.']);
            }
        }
        
        $task = Task::create([
            'taskTitle' => $request->taskTitle,
            'taskDescription' => $request->taskDescription,
            'client_id' => $request->client_id,
            'assignedTo' => $request->assignedTo,
            'formID' => $request->formID,
            'service_request_id' => $request->service_request_id,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => $request->status,
            'allocated_budget' => $request->allocated_budget,
            'createdBy' => Auth::id(),
        ]);
        
        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task created successfully.');
    }
    
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $adiutors = User::where('role', 'adiutor')->orderBy('fullName')->get();
        $forms = Form::all();
        
        return view('admin.tasks.edit', compact('task', 'clients', 'adiutors', 'forms'));
    }
    
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        $request->validate([
            'taskTitle' => 'required|string|max:255',
            'taskDescription' => 'required|string',
            'client_id' => 'required|exists:users,id',
            'assignedTo' => 'nullable|exists:users,id',
            'formID' => 'nullable|exists:forms,formID',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $task->update($request->only([
            'taskTitle', 'taskDescription', 'client_id', 'assignedTo', 'formID', 
            'priority', 'deadline', 'status', 'notes'
        ]));
        
        return redirect()->route('admin.tasks.show', $task->taskID)
                        ->with('success', 'Task updated successfully.');
    }
    
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        
        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task deleted successfully.');
    }
    
    public function assign(Request $request, $id)
    {
        $request->validate([
            'assignedTo' => 'required|exists:users,id',
        ]);
        
        $task = Task::findOrFail($id);
        $task->update([
            'assignedTo' => $request->assignedTo,
            'status' => 'in_progress',
            'dateAssigned' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Task assigned successfully.');
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);
        
        $task = Task::findOrFail($id);
        $oldStatus = $task->status;
        
        $task->update([
            'status' => $request->status,
            'completedAt' => $request->status === 'completed' ? now() : null,
        ]);
        
        return redirect()->back()->with('success', 'Task status updated successfully.');
    }
    
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:assign,status_update,delete',
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,taskID',
            'assignedTo' => 'required_if:action,assign|exists:users,id',
            'status' => 'required_if:action,status_update|in:pending,in_progress,completed,cancelled',
        ]);
        
        $tasks = Task::whereIn('taskID', $request->task_ids);
        
        switch ($request->action) {
            case 'assign':
                $tasks->update([
                    'assignedTo' => $request->assignedTo,
                    'status' => 'in_progress',
                    'dateAssigned' => now(),
                ]);
                $message = 'Tasks assigned successfully.';
                break;
                
            case 'status_update':
                $updateData = ['status' => $request->status];
                if ($request->status === 'completed') {
                    $updateData['completedAt'] = now();
                }
                $tasks->update($updateData);
                $message = 'Task statuses updated successfully.';
                break;
                
            case 'delete':
                $tasks->delete();
                $message = 'Tasks deleted successfully.';
                break;
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Update task notes.
     */
    public function updateNotes(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);
        
        $task = Task::findOrFail($id);
        $task->update([
            'notes' => $request->notes
        ]);
        
        return redirect()->back()->with('success', 'Task notes updated successfully.');
    }
    
    /**
     * Update task budget allocation.
     */
    public function updateBudget(Request $request, $id)
    {
        $request->validate([
            'allocated_budget' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
        ]);
        
        $task = Task::findOrFail($id);
        
        // Check budget allocation if service request is specified
        if ($task->service_request_id && $request->allocated_budget) {
            $serviceRequest = $task->serviceRequest;
            $currentAllocated = $serviceRequest->getTotalAllocatedBudget() - ($task->allocated_budget ?? 0);
            $newTotal = $currentAllocated + $request->allocated_budget;
            
            if ($serviceRequest->approved_budget && $newTotal > $serviceRequest->approved_budget) {
                return redirect()->back()
                    ->withErrors(['allocated_budget' => 'Total allocated budget would exceed project budget.']);
            }
        }
        
        $task->update([
            'allocated_budget' => $request->allocated_budget,
            'actual_cost' => $request->actual_cost,
        ]);
        
        return redirect()->back()->with('success', 'Task budget updated successfully.');
    }
    
    /**
     * Get budget overview for a service request.
     */
    public function budgetOverview($serviceRequestId)
    {
        $serviceRequest = \App\Models\ServiceRequest::with('tasks')->findOrFail($serviceRequestId);
        
        $budgetData = [
            'approved_budget' => $serviceRequest->approved_budget,
            'total_allocated' => $serviceRequest->getTotalAllocatedBudget(),
            'remaining_budget' => $serviceRequest->getRemainingBudget(),
            'tasks' => $serviceRequest->tasks->map(function ($task) {
                return [
                    'id' => $task->taskID,
                    'title' => $task->taskTitle,
                    'allocated_budget' => $task->allocated_budget,
                    'actual_cost' => $task->actual_cost,
                    'budget_utilization' => $task->getBudgetUtilization(),
                    'is_within_budget' => $task->isWithinBudget(),
                ];
            }),
        ];
        
        return response()->json($budgetData);
    }
}