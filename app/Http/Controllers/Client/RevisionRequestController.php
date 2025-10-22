<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\RevisionRequest;
use App\Models\User;
use App\Notifications\RevisionRequestedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
