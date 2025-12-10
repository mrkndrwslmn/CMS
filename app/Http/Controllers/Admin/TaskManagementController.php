<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\User;
use App\Models\Project;
use App\Mail\TaskAssigned;
use App\Mail\TaskCompleted;
use App\Mail\TaskDeadlineChangedMail;
use App\Mail\TaskPriorityUrgentMail;
use App\Notifications\TaskCreatedNotification;
use App\Notifications\TaskUpdatedNotification;
use App\Notifications\TaskDeletedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        
        // Project filter
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        
        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        $tasks = $query->paginate(15)->withQueryString();
        
        // Get filter options
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $adiutors = User::where('role', 'adiutor')->orderBy('fullName')->get();
        $projects = Project::orderBy('title')->get();
        
        // Get current project if filtered
        $currentProject = null;
        if ($request->filled('project_id')) {
            $currentProject = Project::find($request->project_id);
        }
        
        // Get statistics (filtered if project_id is set)
        $statsQuery = Task::query();
        if ($request->filled('project_id')) {
            $statsQuery->where('project_id', $request->project_id);
        }
        
        $stats = [
            'total_tasks' => (clone $statsQuery)->count(),
            'pending_tasks' => (clone $statsQuery)->where('status', 'pending')->count(),
            'in_progress_tasks' => (clone $statsQuery)->where('status', 'in_progress')->count(),
            'completed_tasks' => (clone $statsQuery)->where('status', 'completed')->count(),
        ];
        
        return view('admin.tasks.index', compact('tasks', 'clients', 'adiutors', 'projects', 'currentProject', 'stats'));
    }
    
    public function show($id)
    {
        $task = Task::with(['client', 'assignedUser', 'project.serviceRequest', 'creator', 'phase', 'subtasks'])->findOrFail($id);
        
        // Get ALL documents for this task (both deliverables and regular documents)
        $allDocuments = \App\Models\Document::where('taskID', $id)
            ->where('is_archived', false)
            ->with('uploader')
            ->orderByDesc('is_deliverable') // Deliverables first
            ->orderByDesc('created_at')
            ->get();
        
        // Get task history/activity log if available
        $activities = []; // This could be implemented with a separate Activity model
        
        // Get only team members of this task's project for assignment dropdown
        $adiutors = DB::table('users')
            ->join('project_assignments', 'users.id', '=', 'project_assignments.adiutor_id')
            ->where('project_assignments.project_id', $task->project_id)
            ->whereIn('project_assignments.status', ['assigned', 'accepted', 'in_progress'])
            ->select('users.id', 'users.fullName')
            ->orderBy('users.fullName')
            ->get();
        
        return view('admin.tasks.show', compact('task', 'activities', 'adiutors', 'allDocuments'));
    }
    
    public function create(Request $request)
    {
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        // Only show projects that are not completed or cancelled (can still have tasks added)
        $projects = Project::with('client')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Check if project_id is passed in the URL
        $preSelectedProjectId = $request->query('project_id');
        $preSelectedProject = null;
        
        if ($preSelectedProjectId) {
            $preSelectedProject = Project::find($preSelectedProjectId);
        }
        
        return view('admin.tasks.create', compact('clients', 'projects', 'preSelectedProject'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'taskTitle' => 'required|string|max:255',
            'taskDescription' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'phase_id' => 'nullable|exists:project_milestones,id',
            'assignedTo' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'nullable|date|after:today',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'allocated_budget' => 'nullable|numeric|min:0',
            'max_hours' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'payment_type' => 'nullable|in:hourly,fixed,none',
            'hourly_rate' => 'nullable|numeric|min:0',
            'budget_cap' => 'nullable|numeric|min:0',
            'fixed_budget' => 'nullable|numeric|min:0',
            'requires_time_tracking' => 'nullable|boolean',
            // Subtasks validation
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required_with:subtasks|string|max:255',
            'subtasks.*.description' => 'nullable|string|max:1000',
        ]);
        
        // Get project and client_id from project
        $project = Project::with('serviceRequest')->findOrFail($request->project_id);
        
        // Validate phase_id if project has milestone payment
        if ($project->serviceRequest && $project->serviceRequest->payment_type === 'milestone_payment') {
            if (!$request->phase_id) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['phase_id' => 'Phase selection is required for milestone payment projects.']);
            }
            
            // Validate that the phase belongs to this project
            $phaseExists = DB::table('project_milestones')
                ->where('id', $request->phase_id)
                ->where('project_id', $project->id)
                ->exists();
            
            if (!$phaseExists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['phase_id' => 'The selected phase does not belong to this project.']);
            }
        }
        
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
        
        // Handle payment configuration
        $paymentType = $request->payment_type ?? 'hourly';
        $taskData = [
            'project_id' => $request->project_id,
            'phase_id' => $request->phase_id,
            'taskTitle' => $request->taskTitle,
            'taskDescription' => $request->taskDescription,
            'client_id' => $project->client_id,
            'assignedTo' => $request->assignedTo,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => $request->status,
            'notes' => $request->notes,
            'max_hours' => $request->max_hours,
            'createdBy' => Auth::id(),
            'dateAssigned' => now(), 
        ];
        
        // Apply payment configuration based on type
        if ($paymentType === 'hourly') {
            $taskData['hourly_rate'] = $request->hourly_rate;
            $taskData['requires_time_tracking'] = $request->has('requires_time_tracking');
            $taskData['use_fixed_budget'] = false;
            $taskData['allocated_budget'] = $request->budget_cap ?? $request->allocated_budget;
        } elseif ($paymentType === 'fixed') {
            $taskData['allocated_budget'] = $request->fixed_budget ?? $request->allocated_budget;
            $taskData['use_fixed_budget'] = true;
            $taskData['requires_time_tracking'] = false;
        } else {
            // No payment
            $taskData['use_fixed_budget'] = false;
            $taskData['requires_time_tracking'] = false;
            $taskData['allocated_budget'] = $request->allocated_budget;
        }
        
        $task = Task::create($taskData);
        
        // Create subtasks if provided
        if ($request->has('subtasks') && is_array($request->subtasks)) {
            $sortOrder = 0;
            foreach ($request->subtasks as $subtaskData) {
                if (!empty($subtaskData['title'])) {
                    Subtask::create([
                        'task_id' => $task->taskID,
                        'title' => $subtaskData['title'],
                        'description' => $subtaskData['description'] ?? null,
                        'is_completed' => false,
                        'sort_order' => $sortOrder++,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        }
        
        // 🔔 Notify all admins about new task creation
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            $admin->notify(new TaskCreatedNotification($task, Auth::user()->fullName));
        }

        // 🔔 Notify client about new task
        $client = User::find($project->client_id);
        if ($client) {
            $client->notify(new TaskCreatedNotification($task, 'Admin'));
        }
        
        // Send email notification if task is assigned to someone
        if ($request->assignedTo) {
            try {
                $assignee = User::findOrFail($request->assignedTo);
                $assignedBy = Auth::user();
                Mail::to($assignee->email)->send(new TaskAssigned($task, $assignee, $assignedBy));
            } catch (\Exception $e) {
                // Log the error but don't fail the task creation
                \Log::error('Failed to send task assignment email: ' . $e->getMessage());
            }
        }
        
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
            'project_id' => 'required|exists:projects,id',
            'phase_id' => 'nullable|exists:project_milestones,id',
            'assignedTo' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'completion_notes' => 'nullable|string',
            'allocated_budget' => 'nullable|numeric|min:0',
            'max_hours' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'completedAt' => 'nullable|date',
        ]);

        // Track changes for notifications
        $changes = [];
        $oldDeadline = $task->deadline;
        $oldPriority = $task->priority;
        $oldAssignedTo = $task->assignedTo;
        $oldStatus = $task->status;
        
        if ($task->taskTitle !== $request->taskTitle) {
            $changes['title'] = ['old' => $task->taskTitle, 'new' => $request->taskTitle];
        }
        if ($task->priority !== $request->priority) {
            $changes['priority'] = ['old' => $task->priority, 'new' => $request->priority];
        }
        if ($task->deadline != $request->deadline) {
            $changes['deadline'] = ['old' => $task->deadline, 'new' => $request->deadline];
        }
        if ($task->assignedTo != $request->assignedTo) {
            $changes['assigned_to'] = ['old' => $task->assignedUser->fullName ?? 'Unassigned', 'new' => $request->assignedTo ? User::find($request->assignedTo)->fullName : 'Unassigned'];
        }
        if ($task->status !== $request->status) {
            $changes['status'] = ['old' => $task->status, 'new' => $request->status];
        }
        
        // Get project info for validation
        $project = Project::with('serviceRequest')->findOrFail($request->project_id);
        
        
        $updateData = [
            'project_id' => $request->project_id,
            'phase_id' => $request->phase_id,
            'taskTitle' => $request->taskTitle,
            'taskDescription' => $request->taskDescription,
            'assignedTo' => $request->assignedTo,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => $request->status,
            'notes' => $request->notes,
            'completion_notes' => $request->completion_notes,
            'allocated_budget' => $request->allocated_budget,
            'max_hours' => $request->max_hours,
            'actual_cost' => $request->actual_cost,
            'progress_percentage' => $request->progress_percentage,
        ];
        
        // Update client_id if project changed
        if ($request->project_id != $task->project_id) {
            $updateData['client_id'] = $project->client_id;
        }
        
        // Update dateAssigned if assigning to someone new
        if ($request->assignedTo && $request->assignedTo != $task->assignedTo) {
            $updateData['dateAssigned'] = now();
        }
        
        // Update completedAt based on input or status
        if ($request->filled('completedAt')) {
            $updateData['completedAt'] = $request->completedAt;
        } elseif ($request->status === 'completed' && $task->status !== 'completed') {
            $updateData['completedAt'] = now();
        } elseif ($request->status !== 'completed') {
            $updateData['completedAt'] = null;
        }
        
        $task->update($updateData);
        
        // Recalculate earnings when task status changes to completed
        // This converts allocated_budget to actual_cost for fixed-budget tasks
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $task->recalculateEarnings();
        }

        // 🔔 Notify stakeholders about task updates if there were changes
        if (!empty($changes)) {
            // Notify admins
            $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
            foreach ($admins as $admin) {
                $admin->notify(new TaskUpdatedNotification($task, $changes, Auth::user()->fullName));
            }

            // Notify client
            $client = User::find($task->client_id);
            if ($client) {
                $client->notify(new TaskUpdatedNotification($task, $changes, Auth::user()->fullName));
            }

            // Notify assignee if different from current user
            if ($task->assignedTo && $task->assignedTo != Auth::id()) {
                $assignee = User::find($task->assignedTo);
                if ($assignee) {
                    $assignee->notify(new TaskUpdatedNotification($task, $changes, Auth::user()->fullName));
                }
            }
        }
        
        // Send email notifications for important changes
        try {
            // Send task assignment email if assignee changed
            if ($request->assignedTo && $request->assignedTo != $oldAssignedTo) {
                $assignee = User::findOrFail($request->assignedTo);
                $assignedBy = Auth::user();
                $task->refresh(); // Get updated task data
                Mail::to($assignee->email)->send(new TaskAssigned($task, $assignee, $assignedBy));
            }

            // Send deadline change email if deadline changed significantly
            if ($request->deadline && $oldDeadline && $request->deadline != $oldDeadline && $task->assignedTo) {
                $assignee = User::find($task->assignedTo);
                if ($assignee) {
                    Mail::to($assignee->email)->send(new TaskDeadlineChangedMail($task, $oldDeadline, $request->deadline));
                }
            }

            // Send urgent priority email if priority changed to urgent
            if ($request->priority === 'urgent' && $oldPriority !== 'urgent' && $task->assignedTo) {
                $assignee = User::find($task->assignedTo);
                if ($assignee) {
                    Mail::to($assignee->email)->send(new TaskPriorityUrgentMail($task));
                }
            }
            
            // Send task completion email if status changed to completed
            if ($request->status === 'completed' && $oldStatus !== 'completed' && $task->assignedUser) {
                $task->refresh(); // Get updated task data
                Mail::to($task->client->email)->send(new TaskCompleted($task, $task->assignedUser));
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the update
            \Log::error('Failed to send task update email: ' . $e->getMessage());
        }
        
        return redirect()->route('admin.tasks.show', $task->taskID)
                        ->with('success', 'Task updated successfully.');
    }
    
    public function destroy($id)
    {
        $task = Task::with(['assignedUser', 'project'])->findOrFail($id);
        
        // 🔔 Notify stakeholders before deletion
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            $admin->notify(new TaskDeletedNotification($task, Auth::user()->fullName));
        }

        // Notify client
        $client = User::find($task->client_id);
        if ($client) {
            $client->notify(new TaskDeletedNotification($task, Auth::user()->fullName));
        }

        // Notify assignee if active
        if ($task->assignedTo && $task->status === 'in_progress') {
            $assignee = User::find($task->assignedTo);
            if ($assignee) {
                $assignee->notify(new TaskDeletedNotification($task, Auth::user()->fullName));
            }
        }

        $task->delete();
        
        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task deleted successfully.');
    }
    
    public function assign(Request $request, $id)
    {
        $request->validate([
            'assignedTo' => 'required|exists:users,id',
        ]);
        
        $task = Task::with(['project', 'client'])->findOrFail($id);
        $assignee = User::findOrFail($request->assignedTo);
        $assignedBy = Auth::user();
        
        $task->update([
            'assignedTo' => $request->assignedTo,
            'status' => 'in_progress',
            'dateAssigned' => now(),
        ]);
        
        // Send email notification to assigned adiutor
        try {
            Mail::to($assignee->email)->send(new TaskAssigned($task, $assignee, $assignedBy));
        } catch (\Exception $e) {
            // Log the error but don't fail the assignment
            \Log::error('Failed to send task assignment email: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Task assigned successfully.');
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'confirm_no_deliverables' => 'nullable|boolean', // Confirmation flag
        ]);
        
        $task = Task::with(['project', 'client', 'assignedUser', 'deliverables'])->findOrFail($id);
        $oldStatus = $task->status;
        
        // Check for deliverables when completing a task
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            if (!$task->hasDeliverables() && !$request->boolean('confirm_no_deliverables')) {
                // Return warning that requires confirmation
                return response()->json([
                    'requires_confirmation' => true,
                    'message' => 'This task has no deliverables attached. Are you sure you want to mark it as completed?',
                    'task_id' => $task->taskID,
                ], 422);
            }
        }
        
        $task->update([
            'status' => $request->status,
            'completedAt' => $request->status === 'completed' ? now() : null,
        ]);
        
        // Recalculate earnings when task status changes to completed
        // This converts allocated_budget to actual_cost for fixed-budget tasks
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $task->recalculateEarnings();
        }
        
        // Update project progress when task is completed
        if ($request->status === 'completed' && $oldStatus !== 'completed' && $task->project) {
            $task->project->syncProgressToAssignments();
        }
        
        // Send email notification when task is marked as completed
        if ($request->status === 'completed' && $oldStatus !== 'completed' && $task->assignedUser) {
            try {
                Mail::to($task->client->email)->send(new TaskCompleted($task, $task->assignedUser));
            } catch (\Exception $e) {
                // Log the error but don't fail the status update
                \Log::error('Failed to send task completion email: ' . $e->getMessage());
            }
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully.',
                'task' => $task->fresh(),
            ]);
        }
        
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

    /**
     * Reorder tasks within a project via drag-and-drop
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_ids' => 'required|array|min:1',
            'task_ids.*' => 'required|integer|exists:tasks,taskID',
        ]);

        $projectId = $request->project_id;
        $taskIds = $request->task_ids;

        try {
            DB::transaction(function () use ($projectId, $taskIds) {
                // Verify all tasks belong to the specified project
                $validTasks = Task::where('project_id', $projectId)
                    ->whereIn('taskID', $taskIds)
                    ->count();

                if ($validTasks !== count($taskIds)) {
                    throw new \InvalidArgumentException('Some tasks do not belong to the specified project.');
                }

                // Update sort_order for each task based on new position
                foreach ($taskIds as $index => $taskId) {
                    Task::where('taskID', $taskId)
                        ->where('project_id', $projectId)
                        ->update(['sort_order' => $index + 1]);
                }

                \Log::info('Tasks reordered', [
                    'project_id' => $projectId,
                    'task_count' => count($taskIds),
                    'reordered_by' => Auth::id(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Tasks reordered successfully.',
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Failed to reorder tasks', [
                'project_id' => $projectId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder tasks. Please try again.',
            ], 500);
        }
    }
}