<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Notifications\TaskCompletedNotification;
use App\Notifications\BudgetChangeRequestNotification;
use App\Notifications\ProjectAcceptedNotification;
use App\Notifications\ProjectDeclinedNotification;
use App\Notifications\ProjectProgressUpdateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Show task/project details
     */
    public function show($assignmentId)
    {
        $user = Auth::user();
        
        // Get assignment with project and client details
        $assignment = DB::table('project_assignments')
            ->join('projects', 'project_assignments.project_id', '=', 'projects.id')
            ->join('users', 'projects.client_id', '=', 'users.id')
            ->where('project_assignments.id', $assignmentId)
            ->where('project_assignments.adiutor_id', $user->id)
            ->select(
                'projects.*',
                'project_assignments.id as assignment_id',
                'project_assignments.status as assignment_status',
                'project_assignments.agreed_rate',
                'project_assignments.start_date',
                'project_assignments.expected_completion',
                'project_assignments.progress_percentage',
                'project_assignments.notes as assignment_notes',
                'project_assignments.created_at as assigned_at',
                'users.fullName as client_name',
                'users.email as client_email',
                'users.phoneNumber as client_phone'
            )
            ->first();
        
        if (!$assignment) {
            abort(404, 'Assignment not found');
        }
        
        return view('adiutor.tasks.show', compact('user', 'assignment'));
    }

    /**
     * Accept project assignment
     */
    public function accept($assignmentId)
    {
        $user = Auth::user();
        
        // Verify assignment belongs to this adiutor
        $assignment = DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->where('adiutor_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or already processed'
            ], 404);
        }
        
        // Update assignment status
        DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->update([
                'status' => 'active',
                'start_date' => now(),
                'updated_at' => now()
            ]);
        
        // Create notification for all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ProjectAcceptedNotification($user));
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Project accepted successfully'
        ]);
    }

    /**
     * Decline project assignment
     */
    public function decline(Request $request, $assignmentId)
    {
        $user = Auth::user();
        
        // Verify assignment belongs to this adiutor
        $assignment = DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->where('adiutor_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or already processed'
            ], 404);
        }
        
        $reason = $request->input('reason', 'No reason provided');
        
        // Update assignment status
        DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->update([
                'status' => 'declined',
                'notes' => 'Declined by adiutor. Reason: ' . $reason,
                'updated_at' => now()
            ]);
        
        // Create notification for all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ProjectDeclinedNotification($user, $reason));
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Project declined successfully'
        ]);
    }

    /**
     * Update project progress
     */
    public function updateProgress(Request $request, $assignmentId)
    {
        $user = Auth::user();
        
        $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);
        
        // Verify assignment belongs to this adiutor
        $assignment = DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->where('adiutor_id', $user->id)
            ->where('status', 'active')
            ->first();
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or not active'
            ], 404);
        }
        
        $progress = $request->input('progress');
        $updateData = [
            'progress_percentage' => $progress,
            'updated_at' => now()
        ];
        
        // If progress is 100%, mark as completed
        if ($progress == 100) {
            $updateData['status'] = 'completed';
            $updateData['completion_date'] = now();
        }
        
        // Update assignment
        DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->update($updateData);
        
        // Create notification for client
        $project = DB::table('projects')->where('id', $assignment->project_id)->first();
        if ($project) {
            $client = User::find($project->client_id);
            if ($client) {
                $client->notify(new ProjectProgressUpdateNotification($project->id, $progress));
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully'
        ]);
    }

    /**
     * Check if adiutor is a team member
     */
    protected function isTeamMember($projectId)
    {
        return DB::table('project_assignments')
            ->where('project_id', $projectId)
            ->where('adiutor_id', Auth::id())
            ->whereIn('status', ['assigned', 'accepted', 'in_progress'])
            ->exists();
    }

    /**
     * Create a new task (adiutor can create tasks for their projects)
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'taskTitle' => 'required|string|max:255',
            'taskDescription' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'nullable|date|after:today',
            'allocated_budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        // Verify adiutor is team member
        if (!$this->isTeamMember($request->project_id)) {
            return redirect()->back()
                ->withErrors(['error' => 'You are not a team member of this project.']);
        }
        
        $project = Project::findOrFail($request->project_id);
        
        $task = Task::create([
            'project_id' => $request->project_id,
            'taskTitle' => $request->taskTitle,
            'taskDescription' => $request->taskDescription,
            'client_id' => $project->client_id,
            'assignedTo' => Auth::id(), // Self-assign
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => 'in_progress',
            'allocated_budget' => $request->allocated_budget,
            'notes' => $request->notes,
            'createdBy' => Auth::id(),
            'dateAssigned' => now(),
        ]);
        
        return redirect()->route('adiutor.tasks.show', $task->taskID)
            ->with('success', 'Task created successfully.');
    }

    /**
     * Mark task as completed
     */
    public function markCompleted(Request $request, $taskId)
    {
        $request->validate([
            'completion_notes' => 'nullable|string|max:1000',
        ]);
        
        $task = Task::findOrFail($taskId);
        
        // Verify task is assigned to this adiutor
        if ($task->assignedTo != Auth::id()) {
            return redirect()->back()
                ->withErrors(['error' => 'This task is not assigned to you.']);
        }
        
        $task->update([
            'status' => 'completed',
            'completedAt' => now(),
            'notes' => $request->completion_notes ? $task->notes . "\n\nCompletion Notes: " . $request->completion_notes : $task->notes,
        ]);
        
        // Create notification for admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new TaskCompletedNotification($task, Auth::user()));
        }
        
        return redirect()->back()
            ->with('success', 'Task marked as completed.');
    }

    /**
     * Request budget change
     */
    public function requestBudgetChange(Request $request, $taskId)
    {
        $request->validate([
            'requested_budget' => 'required|numeric|min:0',
            'reason' => 'required|string|max:500',
        ]);
        
        $task = Task::findOrFail($taskId);
        
        // Verify task is assigned to this adiutor
        if ($task->assignedTo != Auth::id()) {
            return redirect()->back()
                ->withErrors(['error' => 'This task is not assigned to you.']);
        }
        
        // Check if there's already a pending request for this task
        $existingRequest = DB::table('budget_change_requests')
            ->where('task_id', $taskId)
            ->where('adiutor_id', Auth::id())
            ->where('status', 'pending')
            ->first();
            
        if ($existingRequest) {
            return redirect()->back()
                ->withErrors(['error' => 'You already have a pending budget change request for this task.']);
        }
        
        // Create budget change request
        DB::table('budget_change_requests')->insert([
            'task_id' => $taskId,
            'adiutor_id' => Auth::id(),
            'current_budget' => $task->allocated_budget ?? 0,
            'requested_budget' => $request->requested_budget,
            'reason' => $request->reason,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create notification for all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new BudgetChangeRequestNotification(
                $task,
                Auth::user(),
                $task->allocated_budget ?? 0,
                $request->requested_budget
            ));
        }
        
        return redirect()->back()
            ->with('success', 'Budget change request submitted. Awaiting admin approval.');
    }
}

