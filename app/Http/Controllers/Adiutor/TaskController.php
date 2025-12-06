<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\Document;
use App\Models\Project;
use App\Models\User;
use App\Models\BudgetChangeRequest;
use App\Notifications\TaskCompletedNotification;
use App\Notifications\BudgetChangeRequestNotification;
use App\Notifications\ProjectAcceptedNotification;
use App\Notifications\ProjectDeclinedNotification;
use App\Notifications\ProjectProgressUpdateNotification;
use App\Notifications\TaskCreatedNotification;
use App\Notifications\FileUploadedNotification;
use App\Mail\BudgetChangeRequested;
use App\Services\CloudflareR2Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        
        // Get task deliverables (files and links marked as deliverables)
        $deliverables = DB::table('documents')
            ->where('taskID', $taskId)
            ->where('is_deliverable', true)
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get subtasks for this task
        $subtasks = Subtask::where('task_id', $taskId)
            ->orderBy('sort_order')
            ->get();
        
        // Check for pending budget change request
        $pendingBudgetRequest = DB::table('budget_change_requests')
            ->where('task_id', $taskId)
            ->where('status', 'pending')
            ->first();
        
        return view('adiutor.tasks.show', compact('user', 'task', 'assignment', 'deliverables', 'subtasks', 'pendingBudgetRequest'));
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
        
        // Log sensitive action
        \App\Models\AuditLog::logSensitiveAction('project_assignment_accepted', [
            'assignment_id' => $assignmentId,
            'adiutor_id' => $user->id,
            'adiutor_name' => $user->fullName,
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
        
        // 🔔 Notify admins about self-assigned task creation
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new TaskCreatedNotification($task, Auth::user()->fullName . ' (Self-assigned)'));
        }

        // 🔔 Notify client about new task
        $client = User::find($project->client_id);
        if ($client) {
            $client->notify(new TaskCreatedNotification($task, Auth::user()->fullName));
        }
        
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
            'confirm_no_deliverables' => 'nullable|boolean',
        ]);
        
        $task = Task::with('deliverables')->findOrFail($taskId);
        
        // Verify task is assigned to this adiutor
        if ($task->assignedTo != Auth::id()) {
            return redirect()->back()
                ->withErrors(['error' => 'This task is not assigned to you.']);
        }
        
        // Check for deliverables before completing
        if (!$task->hasDeliverables() && !$request->boolean('confirm_no_deliverables')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'requires_confirmation' => true,
                    'message' => 'This task has no deliverables attached. Are you sure you want to mark it as completed?',
                    'task_id' => $task->taskID,
                ], 422);
            }
            
            return redirect()->back()
                ->with('warning', 'This task has no deliverables. Please add deliverables or confirm you want to mark it complete without them.')
                ->with('show_completion_confirmation', true)
                ->with('task_id', $task->taskID);
        }
        
        $task->update([
            'status' => 'completed',
            'completedAt' => now(),
            'notes' => $request->completion_notes ? $task->notes . "\n\nCompletion Notes: " . $request->completion_notes : $task->notes,
        ]);
        
        // Auto-complete any approved revision requests for this task
        $completedRevisions = \App\Models\RevisionRequest::where('task_id', $task->taskID)
            ->where('status', 'approved')
            ->where('assigned_adiutor_id', Auth::id())
            ->get();
        
        foreach ($completedRevisions as $revision) {
            $revision->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completed_by' => Auth::id(),
                'admin_notes' => ($revision->admin_notes ?? '') . "\n\nAuto-completed when task was marked as completed."
            ]);
            
            // Notify the client that revision is completed
            if ($revision->requestedBy) {
                $revision->requestedBy->notify(new \App\Notifications\RevisionCompletedNotification($revision));
            }
        }
        
        // Update project progress
        if ($task->project) {
            $task->project->syncProgressToAssignments();
        }
        
        // Create notification for admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new TaskCompletedNotification($task, Auth::user()));
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task marked as completed.',
            ]);
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
        $budgetRequest = BudgetChangeRequest::create([
            'task_id' => $taskId,
            'adiutor_id' => Auth::id(),
            'current_budget' => $task->allocated_budget ?? 0,
            'requested_budget' => $request->requested_budget,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Log sensitive action
        \App\Models\AuditLog::logSensitiveAction('budget_change_requested', [
            'task_id' => $taskId,
            'current_budget' => $task->allocated_budget ?? 0,
            'requested_budget' => $request->requested_budget,
            'difference' => $request->requested_budget - ($task->allocated_budget ?? 0),
            'reason' => $request->reason,
        ], $budgetRequest);
        
        // Send email notifications to all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            // Send notification for dashboard and email
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
            'is_deliverable' => 'nullable|boolean',
        ]);
        
        $user = Auth::user();
        $isDeliverable = $request->boolean('is_deliverable');
        
        // Verify task is assigned to this adiutor
        $task = DB::table('tasks')
            ->where('taskID', $taskId)
            ->where('assignedTo', $user->id)
            ->first();
            
        if (!$task) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Task not found or you do not have access to it.'], 404);
            }
            return redirect()->back()
                ->withErrors(['error' => 'Task not found or you do not have access to it.']);
        }
        
        // Store the file using Cloudflare R2
        $file = $request->file('file');
        $r2Service = new CloudflareR2Service();
        
        // Get task details for proper R2 organization
        $task = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.taskID', $taskId)
            ->select('tasks.*', 'projects.service_request_id')
            ->first();
        
        $serviceRequestId = $task->service_request_id ?? 'unknown';
        $uploadResult = $r2Service->uploadDocument($file, $serviceRequestId, $taskId);
        
        if ($uploadResult['success']) {
            // Save to documents table with R2 URL
            DB::table('documents')->insert([
                'taskID' => $taskId,
                'uploaded_by' => $user->id,
                'fileName' => $uploadResult['original_name'],
                'filePath' => $uploadResult['url'], // Store R2 URL
                'fileType' => $uploadResult['mime_type'],
                'fileSize' => $uploadResult['size'],
                'description' => $request->description,
                'is_deliverable' => $isDeliverable,
                'deliverable_type' => $isDeliverable ? 'file' : null,
                'is_approved' => false, // Deliverables require approval
                'is_archived' => false,
                'uploadedAt' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 🔔 Notify client about file upload (especially for deliverables)
            $task = DB::table('tasks')
                ->join('projects', 'tasks.project_id', '=', 'projects.id')
                ->where('tasks.taskID', $taskId)
                ->select('tasks.*', 'projects.client_id', 'projects.title as project_title')
                ->first();

            if ($task) {
                $client = User::find($task->client_id);
                if ($client && $isDeliverable) {
                    // Only notify client about deliverables (after admin approval - this is a placeholder)
                    // For now, notify that a deliverable is pending approval
                    $client->notify(new FileUploadedNotification(
                        $uploadResult['original_name'],
                        $task->taskTitle,
                        $taskId,
                        Auth::user()->fullName,
                        true // is deliverable
                    ));
                }

                // Notify admins about any file upload (especially deliverables that need approval)
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new FileUploadedNotification(
                        $uploadResult['original_name'],
                        $task->taskTitle,
                        $taskId,
                        Auth::user()->fullName,
                        $isDeliverable
                    ));
                }
            }
            
            $successMessage = $isDeliverable 
                ? 'Deliverable uploaded successfully. It will be visible to the client once approved by an admin.'
                : 'File uploaded successfully to cloud storage.';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMessage]);
            }
            
            return redirect()->back()->with('success', $successMessage);
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to upload file: ' . $uploadResult['error']], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Failed to upload file: ' . $uploadResult['error']]);
        }
    }

    /**
     * Add a link deliverable for task
     */
    public function addLinkDeliverable(Request $request, $taskId)
    {
        $request->validate([
            'link_url' => 'required|url|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);
        
        $user = Auth::user();
        
        // Verify task is assigned to this adiutor
        $task = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.taskID', $taskId)
            ->where('tasks.assignedTo', $user->id)
            ->select('tasks.*', 'projects.client_id', 'projects.title as project_title')
            ->first();
            
        if (!$task) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Task not found or you do not have access to it.'], 404);
            }
            return redirect()->back()
                ->withErrors(['error' => 'Task not found or you do not have access to it.']);
        }
        
        // Create document entry for link deliverable
        DB::table('documents')->insert([
            'taskID' => $taskId,
            'uploaded_by' => $user->id,
            'fileName' => $request->title,
            'filePath' => '', // Empty string for links (column cannot be null)
            'fileType' => 'link',
            'fileSize' => 0,
            'description' => $request->description,
            'link_url' => $request->link_url,
            'is_deliverable' => true,
            'deliverable_type' => 'link',
            'is_approved' => false, // Deliverables require approval
            'is_archived' => false,
            'uploadedAt' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Notify admins about link deliverable that needs approval
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new FileUploadedNotification(
                $request->title . ' (Link)',
                $task->taskTitle,
                $taskId,
                Auth::user()->fullName,
                true // is deliverable
            ));
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Link deliverable added successfully. It will be visible to the client once approved by an admin.'
            ]);
        }
        
        return redirect()->back()->with('success', 'Link deliverable added successfully. It will be visible to the client once approved by an admin.');
    }

    /**
     * Download task file
     */
    public function downloadFile($fileId)
    {
        $user = Auth::user();
        
        // Get file and verify access using Document model
        $document = Document::join('tasks', 'documents.taskID', '=', 'tasks.taskID')
            ->where('documents.documentID', $fileId)
            ->where('tasks.assignedTo', $user->id)
            ->where('documents.is_archived', false)
            ->select('documents.*')
            ->first();
            
        if (!$document) {
            abort(404, 'File not found or you do not have access to it.');
        }
        
        // Use the model's method to get the proper download URL
        return redirect($document->getDownloadUrl());
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
        // Check if file is stored in R2 (has URL format) or local storage
        $isR2File = $file->filePath && (
            str_starts_with($file->filePath, 'https://') || 
            str_starts_with($file->filePath, 'http://')
        );
        
        if ($isR2File) {
            // File is in R2 - we just remove the database record
            // R2 files are managed by Cloudflare
            \Log::info('Deleting R2 file from database: ' . $file->filePath);
        } else {
            // Legacy: Delete local file
            $filePath = storage_path('app/public/' . $file->filePath);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete record from database
        DB::table('documents')
            ->where('documentID', $fileId)
            ->delete();
        
        return redirect()->back()
            ->with('success', 'File deleted successfully.');
    }

    /**
     * Update task progress percentage
     */
    public function updateTaskProgress(Request $request, $taskId)
    {
        $user = Auth::user();
        
        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
            'confirm_no_deliverables' => 'nullable|boolean'
        ]);

        // Get task with deliverables and verify ownership
        $task = Task::with('deliverables')
            ->where('taskID', $taskId)
            ->where('assignedTo', $user->id)
            ->first();

        if (!$task) {
            return response()->json(['error' => 'Task not found or you do not have permission to update it.'], 404);
        }

        $progressPercentage = $request->input('progress_percentage');

        // If trying to set to 100% (complete), check for deliverables
        if ($progressPercentage == 100 && !$task->hasDeliverables() && !$request->boolean('confirm_no_deliverables')) {
            return response()->json([
                'requires_confirmation' => true,
                'message' => 'This task has no deliverables, documents, or links attached. Are you sure you want to mark it as 100% complete?',
                'task_id' => $task->taskID,
            ], 422);
        }

        // Update task progress
        DB::table('tasks')
            ->where('taskID', $taskId)
            ->update([
                'progress_percentage' => $progressPercentage,
                'updated_at' => now()
            ]);

        // If progress is 100%, update status to completed
        if ($progressPercentage == 100) {
            DB::table('tasks')
                ->where('taskID', $taskId)
                ->update([
                    'status' => 'completed',
                    'completedAt' => now()
                ]);
        } elseif ($progressPercentage > 0 && $task->status === 'pending') {
            // If task was pending and now has progress, mark as in_progress
            DB::table('tasks')
                ->where('taskID', $taskId)
                ->update(['status' => 'in_progress']);
        }

        // Create audit log entry using direct database insert
        try {
            DB::table('audit_logs')->insert([
                'user_id' => $user->id,
                'action' => 'task_progress_updated',
                'auditable_type' => 'App\Models\Task',
                'auditable_id' => $taskId,
                'old_values' => json_encode(['progress_percentage' => $task->progress_percentage]),
                'new_values' => json_encode(['progress_percentage' => $progressPercentage]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't fail the request
            \Log::error('Failed to create audit log: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Task progress updated successfully',
            'progress_percentage' => $progressPercentage
        ]);
    }

    /**
     * Get deliverables for a task
     */
    public function getDeliverables($taskId)
    {
        $user = Auth::user();
        
        // Verify task is assigned to this adiutor
        $task = Task::where('taskID', $taskId)
            ->where('assignedTo', $user->id)
            ->first();
            
        if (!$task) {
            return response()->json(['error' => 'Task not found or you do not have access to it.'], 404);
        }
        
        $deliverables = $task->deliverables()
            ->with('uploader:id,fullName')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->documentID,
                    'title' => $doc->fileName,
                    'description' => $doc->description,
                    'type' => $doc->deliverable_type,
                    'link_url' => $doc->link_url,
                    'file_url' => $doc->deliverable_type === 'file' ? $doc->filePath : null,
                    'is_approved' => $doc->is_approved,
                    'approved_at' => $doc->approved_at,
                    'uploader' => $doc->uploader?->fullName,
                    'created_at' => $doc->created_at,
                ];
            });
        
        return response()->json([
            'success' => true,
            'deliverables' => $deliverables,
            'counts' => $task->getDeliverablesCounts(),
        ]);
    }
}
