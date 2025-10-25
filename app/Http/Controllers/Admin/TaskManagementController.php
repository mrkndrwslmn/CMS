<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['client', 'assignedUser', 'project', 'creator']);
        
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
        $task = Task::with(['client', 'assignedUser', 'project', 'creator', 'documents'])->findOrFail($id);
        
        // Get task history/activity log if available
        $activities = []; // This could be implemented with a separate Activity model
        
        // Get adiutors for assignment dropdown
        $adiutors = User::where('role', 'adiutor')->orderBy('fullName')->get();
        
        return view('admin.tasks.show', compact('task', 'activities', 'adiutors'));
    }
    
    public function create()
    {
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $projects = Project::with('client')->orderBy('created_at', 'desc')->get();
        // Don't fetch adiutors here - let the view handle it dynamically based on selected project
        
        return view('admin.tasks.create', compact('clients', 'projects'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'taskTitle' => 'required|string|max:255',
            'taskDescription' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'assignedTo' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'nullable|date|after:today',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'allocated_budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        // Get project and client_id from project
        $project = Project::findOrFail($request->project_id);
        
        // ⚠️ Validate that assigned adiutor is a team member
        if ($request->assignedTo) {
            if (!$this->isProjectTeamMember($project->id, $request->assignedTo)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['assignedTo' => 'The selected adiutor is not a team member of this project. Please assign them to the project first.']);
            }
        }
        
        // Check budget allocation against project budget
        if ($request->allocated_budget) {
            $totalAllocated = Task::where('project_id', $project->id)->sum('allocated_budget') ?? 0;
            $newTotal = $totalAllocated + $request->allocated_budget;
            
            if ($project->budget && $newTotal > $project->budget) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['allocated_budget' => 'Total allocated budget would exceed project budget.']);
            }
        }
        
        $task = Task::create([
            'project_id' => $request->project_id,
            'taskTitle' => $request->taskTitle,
            'taskDescription' => $request->taskDescription,
            'client_id' => $project->client_id,
            'assignedTo' => $request->assignedTo,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => $request->status,
            'allocated_budget' => $request->allocated_budget,
            'notes' => $request->notes,
            'createdBy' => Auth::id(),
            'dateAssigned' => $request->assignedTo ? now() : null,
        ]);
        
        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task created successfully.');
    }
    
    public function edit($id)
    {
        $task = Task::with('project')->findOrFail($id);
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $projects = Project::with('client')->orderBy('created_at', 'desc')->get();
        
        // ⚠️ Get only team members of this task's project
        $adiutors = DB::table('users')
            ->join('project_assignments', 'users.id', '=', 'project_assignments.adiutor_id')
            ->where('project_assignments.project_id', $task->project_id)
            ->whereIn('project_assignments.status', ['assigned', 'accepted', 'in_progress'])
            ->select('users.id', 'users.fullName')
            ->orderBy('users.fullName')
            ->get();
        
        return view('admin.tasks.edit', compact('task', 'clients', 'adiutors', 'projects'));
    }
    
    public function update(Request $request, $id)
    {
        $task = Task::with('project')->findOrFail($id);
        
        $request->validate([
            'taskTitle' => 'required|string|max:255',
            'taskDescription' => 'required|string',
            'assignedTo' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'allocated_budget' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
        ]);
        
        // ⚠️ Validate that assigned adiutor is a team member (if changing assignment)
        if ($request->assignedTo && $request->assignedTo != $task->assignedTo) {
            if (!$this->isProjectTeamMember($task->project_id, $request->assignedTo)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['assignedTo' => 'The selected adiutor is not a team member of this project. Please assign them to the project first.']);
            }
        }
        
        // Check budget allocation if changed
        if ($request->allocated_budget && $request->allocated_budget != $task->allocated_budget) {
            $project = $task->project;
            $totalAllocated = Task::where('project_id', $project->id)
                                 ->where('taskID', '!=', $task->taskID)
                                 ->sum('allocated_budget') ?? 0;
            $newTotal = $totalAllocated + $request->allocated_budget;
            
            if ($project->budget && $newTotal > $project->budget) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['allocated_budget' => 'Total allocated budget would exceed project budget.']);
            }
        }
        
        $updateData = [
            'taskTitle' => $request->taskTitle,
            'taskDescription' => $request->taskDescription,
            'assignedTo' => $request->assignedTo,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => $request->status,
            'notes' => $request->notes,
            'allocated_budget' => $request->allocated_budget,
            'actual_cost' => $request->actual_cost,
            'progress_percentage' => $request->progress_percentage,
        ];
        
        // Update dateAssigned if assigning to someone new
        if ($request->assignedTo && $request->assignedTo != $task->assignedTo) {
            $updateData['dateAssigned'] = now();
        }
        
        // Update completedAt if status changed to completed
        if ($request->status === 'completed' && $task->status !== 'completed') {
            $updateData['completedAt'] = now();
        } elseif ($request->status !== 'completed') {
            $updateData['completedAt'] = null;
        }
        
        $task->update($updateData);
        
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
        
        $task = Task::with('project')->findOrFail($id);
        
        // Check budget allocation against project budget
        if ($request->allocated_budget) {
            $project = $task->project;
            $totalAllocated = Task::where('project_id', $project->id)
                                 ->where('taskID', '!=', $task->taskID)
                                 ->sum('allocated_budget') ?? 0;
            $newTotal = $totalAllocated + $request->allocated_budget;
            
            if ($project->budget && $newTotal > $project->budget) {
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
     * Get budget overview for a project.
     */
    public function budgetOverview($projectId)
    {
        $project = Project::with('tasks')->findOrFail($projectId);
        
        $totalAllocated = $project->tasks->sum('allocated_budget') ?? 0;
        $totalSpent = $project->tasks->sum('actual_cost') ?? 0;
        $remainingBudget = ($project->budget ?? 0) - $totalAllocated;
        
        $budgetData = [
            'project_budget' => $project->budget,
            'total_allocated' => $totalAllocated,
            'total_spent' => $totalSpent,
            'remaining_budget' => $remainingBudget,
            'budget_utilization_percentage' => $project->budget > 0 ? round(($totalAllocated / $project->budget) * 100, 2) : 0,
            'tasks' => $project->tasks->map(function ($task) {
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

    /**
     * Check if adiutor is a team member of the project
     */
    protected function isProjectTeamMember($projectId, $adiutorId)
    {
        return DB::table('project_assignments')
            ->where('project_id', $projectId)
            ->where('adiutor_id', $adiutorId)
            ->whereIn('status', ['assigned', 'accepted', 'in_progress'])
            ->exists();
    }
}