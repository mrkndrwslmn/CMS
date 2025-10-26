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
     * Display a listing of all tasks assigned to adiutor
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get all tasks across all projects assigned to this adiutor
        $query = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('users as clients', 'projects.client_id', '=', 'clients.id')
            ->join('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->where('tasks.assignedTo', $user->id)
            ->select(
                'tasks.*',
                'projects.title as project_title',
                'projects.status as project_status',
                'clients.fullName as client_name',
                'clients.profilePic as client_photo'
            );
        
        // Filter by project if specified
        if ($request->has('project')) {
            $query->where('tasks.project_id', $request->project);
        }
        
        // Filter by status if specified
        if ($request->has('status') && $request->status != 'all') {
            $query->where('tasks.status', $request->status);
        }
        
        // Filter by priority if specified
        if ($request->has('priority') && $request->priority != 'all') {
            $query->where('tasks.priority', $request->priority);
        }
        
        $tasks = $query->orderBy('tasks.deadline', 'asc')
            ->orderBy('tasks.priority', 'desc')
            ->get();
        
        // Get projects for filter dropdown
        $projects = DB::table('projects')
            ->join('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->select('projects.id', 'projects.title')
            ->get();
        
        return view('adiutor.tasks.index', compact('user', 'tasks', 'projects'));
    }

    /**
     * Show task details
     */
    public function show($taskId)
    {
        $user = Auth::user();
        
        // Get task with project, client, and phase details
        $task = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('users as clients', 'projects.client_id', '=', 'clients.id')
            ->leftJoin('users as assignee', 'tasks.assignedTo', '=', 'assignee.id')
            ->leftJoin('project_milestones as phase', 'tasks.phase_id', '=', 'phase.id')
            ->where('tasks.taskID', $taskId)
            ->where('tasks.assignedTo', $user->id)
            ->select(
                'tasks.*',
                'projects.title as project_title',
                'projects.status as project_status',
                'projects.budget as project_budget',
                'projects.deadline as project_deadline',
                'clients.fullName as client_name',
                'clients.email as client_email',
                'clients.phoneNumber as client_phone',
                'clients.profilePic as client_photo',
                'assignee.fullName as assignee_name',
                'phase.phase_name as phase_name',
                'phase.amount as phase_budget',
                'phase.is_paid as phase_is_paid'
            )
            ->first();
        
        if (!$task) {
            abort(404, 'Task not found or you do not have access to it');
        }
        
        // Get project assignment info
        $assignment = DB::table('project_assignments')
            ->where('project_id', $task->project_id)
            ->where('adiutor_id', $user->id)
            ->first();
        
        // Get task files
        $taskFiles = DB::table('documents')
            ->where('taskID', $taskId)
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Check for pending budget change request
        $pendingBudgetRequest = DB::table('budget_change_requests')
            ->where('task_id', $taskId)
            ->where('status', 'pending')
            ->first();
        
        return view('adiutor.tasks.show', compact('user', 'task', 'assignment', 'taskFiles', 'pendingBudgetRequest'));
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

    /**
     * Upload file for task
     */
    public function uploadFile(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string|max:255',
        ]);
        
        $user = Auth::user();
        
        // Verify task is assigned to this adiutor
        $task = DB::table('tasks')
            ->where('taskID', $taskId)
            ->where('assignedTo', $user->id)
            ->first();
            
        if (!$task) {
            return redirect()->back()
                ->withErrors(['error' => 'Task not found or you do not have access to it.']);
        }
        
        // Store the file
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileName = time() . '_' . $originalName;
        $filePath = $file->storeAs('task_files', $fileName, 'public');
        
        // Save to documents table
        DB::table('documents')->insert([
            'taskID' => $taskId,
            'uploaded_by' => $user->id,
            'fileName' => $originalName,
            'filePath' => $filePath,
            'fileType' => $file->getClientMimeType(),
            'fileSize' => $file->getSize(),
            'description' => $request->description,
            'is_archived' => false,
            'uploadedAt' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Download task file
     */
    public function downloadFile($fileId)
    {
        $user = Auth::user();
        
        // Get file and verify access
        $file = DB::table('documents')
            ->join('tasks', 'documents.taskID', '=', 'tasks.taskID')
            ->where('documents.documentID', $fileId)
            ->where('tasks.assignedTo', $user->id)
            ->where('documents.is_archived', false)
            ->select('documents.*')
            ->first();
            
        if (!$file) {
            abort(404, 'File not found or you do not have access to it.');
        }
        
        $filePath = storage_path('app/public/' . $file->filePath);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }
        
        return response()->download($filePath, $file->fileName);
    }

    /**
     * Delete task file (only if uploaded by current user)
     */
    public function deleteFile($fileId)
    {
        $user = Auth::user();
        
        // Get file and verify ownership
        $file = DB::table('documents')
            ->join('tasks', 'documents.taskID', '=', 'tasks.taskID')
            ->where('documents.documentID', $fileId)
            ->where('tasks.assignedTo', $user->id)
            ->where('documents.uploaded_by', $user->id) // Only allow deletion if they uploaded it
            ->where('documents.is_archived', false)
            ->select('documents.*')
            ->first();
            
        if (!$file) {
            return redirect()->back()
                ->withErrors(['error' => 'File not found or you do not have permission to delete it.']);
        }
        
        // Delete physical file from storage
        $filePath = storage_path('app/public/' . $file->filePath);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete record from database
        DB::table('documents')
            ->where('documentID', $fileId)
            ->delete();
        
        return redirect()->back()
            ->with('success', 'File deleted successfully.');
    }
}
