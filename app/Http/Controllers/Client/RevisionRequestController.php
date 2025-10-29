<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Project;
use App\Models\Task;
use App\Models\RevisionRequest;
use App\Models\User;
use App\Notifications\RevisionRequestedNotification;
use App\Mail\RevisionRequestSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RevisionRequestController extends Controller
{
    /**
     * Show form to request revision for a document
     */
    public function create($documentId)
    {
        $user = Auth::user();
        
        $document = Document::with([
            'task.project.serviceRequest',
            'project.serviceRequest',
            'serviceRequest',
            'uploader'
        ])
        ->where('documentID', $documentId)
        ->where('client_id', $user->id)
        ->firstOrFail();

        // Check if document can be revised
        if (!$document->canRequestRevision()) {
            return redirect()->back()
                ->with('error', 'This document already has a pending or active revision request.');
        }

        // Determine source type and get related info
        $sourceType = null;
        $sourceInfo = null;
        $adiutorId = null;

        if ($document->taskID && $document->task) {
            $sourceType = 'task';
            $sourceInfo = [
                'type' => 'Task',
                'name' => $document->task->title,
                'task_id' => $document->task->taskID,
                'project_id' => $document->task->projectID,
                'service_request_id' => $document->task->project->service_request_id ?? null
            ];
            // Get adiutor from task assignment
            $assignment = $document->task->assignments()->where('status', 'active')->first();
            $adiutorId = $assignment ? $assignment->adiutor_id : null;
        } elseif ($document->project_id && $document->project) {
            $sourceType = 'project';
            $sourceInfo = [
                'type' => 'Project',
                'name' => $document->project->title,
                'project_id' => $document->project->id,
                'service_request_id' => $document->project->service_request_id ?? null
            ];
            // Get adiutor from project assignment
            $assignment = $document->project->assignments()->where('status', 'active')->first();
            $adiutorId = $assignment ? $assignment->adiutor_id : null;
        } elseif ($document->service_request_id && $document->serviceRequest) {
            $sourceType = 'project';
            $sourceInfo = [
                'type' => 'Service Request',
                'name' => $document->serviceRequest->project_name,
                'service_request_id' => $document->service_request_id
            ];
        }

        return view('client.revisions.create', compact('document', 'sourceType', 'sourceInfo', 'adiutorId'));
    }

    /**
     * Store a new revision request
     */
    public function store(Request $request, $documentId)
    {
        $user = Auth::user();
        
        $document = Document::with([
            'task.project.serviceRequest',
            'project.serviceRequest',
            'serviceRequest'
        ])
        ->where('documentID', $documentId)
        ->where('client_id', $user->id)
        ->firstOrFail();

        // Check if document can be revised
        if (!$document->canRequestRevision()) {
            return redirect()->back()
                ->with('error', 'This document already has a pending or active revision request.');
        }

        // Validate input
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
            'requested_due_date' => 'nullable|date|after:today',
        ]);

        try {
            DB::beginTransaction();

            // Determine source type and related IDs
            $sourceType = null;
            $taskId = null;
            $projectId = null;
            $serviceRequestId = null;
            $adiutorId = null;

            if ($document->taskID && $document->task) {
                $sourceType = 'task';
                $taskId = $document->task->taskID;
                $projectId = $document->task->projectID;
                $serviceRequestId = $document->task->project->service_request_id ?? null;
                
                // Get adiutor from task assignment
                $assignment = $document->task->assignments()->where('status', 'active')->first();
                $adiutorId = $assignment ? $assignment->adiutor_id : null;
            } elseif ($document->project_id && $document->project) {
                $sourceType = 'project';
                $projectId = $document->project->id;
                $serviceRequestId = $document->project->service_request_id ?? null;
                
                // Get adiutor from project assignment
                $assignment = $document->project->assignments()->where('status', 'active')->first();
                $adiutorId = $assignment ? $assignment->adiutor_id : null;
            } elseif ($document->service_request_id) {
                $sourceType = 'project';
                $serviceRequestId = $document->service_request_id;
            }

            // Get revision number (count previous revisions + 1)
            $revisionNumber = $document->revisionRequests()->count() + 1;

            // Create revision request
            $revisionRequest = RevisionRequest::create([
                'document_id' => $document->documentID,
                'requested_by' => $user->id,
                'reason' => $validated['reason'],
                'requested_due_date' => $validated['requested_due_date'] ?? null,
                'revision_number' => $revisionNumber,
                'status' => 'pending',
                'task_id' => $taskId,
                'project_id' => $projectId,
                'service_request_id' => $serviceRequestId,
                'source_type' => $sourceType,
                'assigned_adiutor_id' => $adiutorId
            ]);

            // Notify admins about the revision request
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new RevisionRequestedNotification($revisionRequest));
                
                // Email is now sent via the notification system, no need for direct Mail::to()->send()
            }

            // Also notify the adiutor if assigned
            if ($adiutorId) {
                $adiutor = User::find($adiutorId);
                if ($adiutor) {
                    $adiutor->notify(new RevisionRequestedNotification($revisionRequest));
                }
            }

            DB::commit();

            Log::info('Revision request created', [
                'revision_request_id' => $revisionRequest->id,
                'document_id' => $documentId,
                'client_id' => $user->id,
                'source_type' => $sourceType
            ]);

            return redirect()->route('client.documents.show', $documentId)
                ->with('success', 'Revision request submitted successfully. An admin will review it shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create revision request', [
                'document_id' => $documentId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit revision request. Please try again.');
        }
    }

    /**
     * Show revision request details
     */
    public function show($revisionId)
    {
        $user = Auth::user();
        
        $revision = RevisionRequest::with([
            'document',
            'task',
            'project',
            'serviceRequest',
            'requestedBy',
            'reviewedBy',
            'assignedAdiutor',
            'completedBy'
        ])
        ->where('requested_by', $user->id)
        ->findOrFail($revisionId);

        return view('client.revisions.show', compact('revision'));
    }

    /**
     * List all revision requests for the client
     */
    public function index()
    {
        $user = Auth::user();
        
        $revisions = RevisionRequest::with([
            'document',
            'task',
            'project',
            'reviewedBy',
            'assignedAdiutor'
        ])
        ->where('requested_by', $user->id)
        ->latest()
        ->paginate(15);

        return view('client.revisions.index', compact('revisions'));
    }

    /**
     * Store a project-level revision request
     */
    public function storeForProject(Request $request, $projectId)
    {
        $user = Auth::user();
        
        // Debug: Log the request data
        Log::info('Revision request received', [
            'project_id' => $projectId,
            'user_id' => $user->id,
            'request_data' => $request->all()
        ]);
        
        $project = Project::with(['serviceRequest', 'assignments'])
            ->where('id', $projectId)
            ->whereHas('serviceRequest', function($query) use ($user) {
                $query->where('client_id', $user->id);
            })
            ->firstOrFail();

        // Check if project is completed or in review
        if (!in_array($project->status, ['completed', 'review'])) {
            Log::warning('Revision request rejected - invalid project status', [
                'project_id' => $projectId,
                'status' => $project->status
            ]);
            return redirect()->back()
                ->with('error', 'Revisions can only be requested for completed or projects under review.');
        }

        // Validate input
        $validated = $request->validate([
            'revision_scope' => 'required|in:project,task',
            'task_ids' => 'required_if:revision_scope,task|array',
            'task_ids.*' => 'exists:tasks,taskID',
            'reason' => 'required|string|min:20|max:2000',
            'requested_due_date' => 'nullable|date|after:today',
            'priority' => 'nullable|in:normal,high,urgent'
        ]);
        
        Log::info('Validation passed', ['validated_data' => $validated]);

        try {
            DB::beginTransaction();

            // Get assigned adiutor
            $assignment = $project->assignments()->where('status', 'active')->first();
            $adiutorId = $assignment ? $assignment->adiutor_id : null;

            if ($validated['revision_scope'] === 'project') {
                // Create project-wide revision request
                $revisionNumber = RevisionRequest::where('project_id', $projectId)
                    ->whereNull('task_id')
                    ->count() + 1;

                $revisionRequest = RevisionRequest::create([
                    'document_id' => null,
                    'requested_by' => $user->id,
                    'reason' => $validated['reason'],
                    'requested_due_date' => $validated['requested_due_date'] ?? null,
                    'revision_number' => $revisionNumber,
                    'status' => 'pending',
                    'task_id' => null,
                    'project_id' => $projectId,
                    'service_request_id' => $project->service_request_id,
                    'source_type' => 'project',
                    'assigned_adiutor_id' => $adiutorId,
                    'priority' => $validated['priority'] ?? 'normal'
                ]);

                $message = 'Project revision request submitted successfully.';
                
            } else {
                // Create task-specific revision requests
                $revisionsCreated = 0;
                $revisionRequests = [];

                foreach ($validated['task_ids'] as $taskId) {
                    $task = Task::where('taskID', $taskId)
                        ->where('projectID', $projectId)
                        ->first();
                    
                    if (!$task) continue;

                    // Get task-specific adiutor if different
                    $taskAssignment = $task->assignments()->where('status', 'active')->first();
                    $taskAdiutorId = $taskAssignment ? $taskAssignment->adiutor_id : $adiutorId;

                    $revisionNumber = RevisionRequest::where('task_id', $taskId)->count() + 1;

                    $revisionRequest = RevisionRequest::create([
                        'document_id' => null,
                        'requested_by' => $user->id,
                        'reason' => $validated['reason'],
                        'requested_due_date' => $validated['requested_due_date'] ?? null,
                        'revision_number' => $revisionNumber,
                        'status' => 'pending',
                        'task_id' => $taskId,
                        'project_id' => $projectId,
                        'service_request_id' => $project->service_request_id,
                        'source_type' => 'task',
                        'assigned_adiutor_id' => $taskAdiutorId,
                        'priority' => $validated['priority'] ?? 'normal'
                    ]);

                    $revisionRequests[] = $revisionRequest;
                    $revisionsCreated++;
                }

                $revisionRequest = $revisionRequests[0] ?? null; // Use first for notifications
                $message = "Revision request(s) created for {$revisionsCreated} task(s).";
            }

            if (!$revisionRequest) {
                throw new \Exception('Failed to create revision request');
            }

            // Notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new RevisionRequestedNotification($revisionRequest));
                
                // Email is now sent via the notification system, no need for direct Mail::to()->send()
            }

            // Notify assigned adiutor(s)
            if ($validated['revision_scope'] === 'project' && $adiutorId) {
                $adiutor = User::find($adiutorId);
                if ($adiutor) {
                    $adiutor->notify(new RevisionRequestedNotification($revisionRequest));
                }
            } elseif ($validated['revision_scope'] === 'task') {
                $notifiedAdiutors = [];
                foreach ($revisionRequests as $req) {
                    if ($req->assigned_adiutor_id && !in_array($req->assigned_adiutor_id, $notifiedAdiutors)) {
                        $adiutor = User::find($req->assigned_adiutor_id);
                        if ($adiutor) {
                            $adiutor->notify(new RevisionRequestedNotification($req));
                            $notifiedAdiutors[] = $req->assigned_adiutor_id;
                        }
                    }
                }
            }

            DB::commit();

            Log::info('Project revision request created', [
                'project_id' => $projectId,
                'client_id' => $user->id,
                'scope' => $validated['revision_scope'],
                'priority' => $validated['priority'] ?? 'normal'
            ]);

            return redirect()->route('client.projects.show', $projectId)
                ->with('success', $message . ' An admin will review it shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create project revision request', [
                'project_id' => $projectId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit revision request. Please try again.');
        }
    }

    /**
     * Cancel a revision request (only if pending)
     */
    public function cancel($revisionId)
    {
        $user = Auth::user();
        
        $revision = RevisionRequest::where('requested_by', $user->id)
            ->where('status', 'pending')
            ->findOrFail($revisionId);

        $revision->update([
            'status' => 'cancelled'
        ]);

        Log::info('Revision request cancelled by client', [
            'revision_request_id' => $revisionId,
            'client_id' => $user->id
        ]);

        return redirect()->back()
            ->with('success', 'Revision request cancelled successfully.');
    }
}
