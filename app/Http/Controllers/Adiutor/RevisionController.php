<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\RevisionRequest;
use App\Models\Document;
<<<<<<< HEAD
use App\Models\Project;
use App\Notifications\RevisionCompletedNotification;
=======
>>>>>>> 7c71488 (Initial commit from Princess)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RevisionController extends Controller
{
    /**
     * Display a listing of revisions assigned to the adiutor
     */
    public function index(Request $request)
    {
        $adiutor = Auth::user();
        
        $query = RevisionRequest::with([
            'document',
            'requestedBy',
            'task',
            'project',
            'reviewedBy'
        ])->where('assigned_adiutor_id', $adiutor->id);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by source type
        if ($request->has('source_type') && $request->source_type !== 'all') {
            $query->where('source_type', $request->source_type);
        }

        $revisions = $query->latest()->paginate(15);

        // Get counts for tabs
        $statusCounts = [
            'all' => RevisionRequest::where('assigned_adiutor_id', $adiutor->id)->count(),
            'approved' => RevisionRequest::where('assigned_adiutor_id', $adiutor->id)
                ->where('status', 'approved')->count(),
            'completed' => RevisionRequest::where('assigned_adiutor_id', $adiutor->id)
                ->where('status', 'completed')->count(),
        ];

        return view('adiutor.revisions.index', compact('revisions', 'statusCounts'));
    }

    /**
     * Display the specified revision request
     */
    public function show($id)
    {
        $adiutor = Auth::user();
        
        $revision = RevisionRequest::with([
            'document.uploader',
            'requestedBy.clientProfile',
            'task.project.serviceRequest',
            'project.serviceRequest',
            'serviceRequest',
            'reviewedBy'
        ])
        ->where('assigned_adiutor_id', $adiutor->id)
        ->findOrFail($id);

        return view('adiutor.revisions.show', compact('revision'));
    }

    /**
     * Mark revision as completed (after uploading new document version)
     */
    public function complete(Request $request, $id)
    {
        $adiutor = Auth::user();
        
<<<<<<< HEAD
        $revision = RevisionRequest::with(['document', 'task', 'project', 'requestedBy'])
=======
        $revision = RevisionRequest::with(['document', 'task'])
>>>>>>> 7c71488 (Initial commit from Princess)
            ->where('assigned_adiutor_id', $adiutor->id)
            ->where('status', 'approved')
            ->findOrFail($id);

        $validated = $request->validate([
            'completion_notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Update revision status
            $revision->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completed_by' => $adiutor->id,
                'admin_notes' => ($revision->admin_notes ?? '') . "\n\nCompletion Notes: " . ($validated['completion_notes'] ?? 'No notes provided.')
            ]);

<<<<<<< HEAD
            // Handle task or project completion based on source type
            if ($revision->source_type === 'task' && $revision->task) {
=======
            // If task-based, mark task as completed again
            if ($revision->isTaskBased() && $revision->task) {
>>>>>>> 7c71488 (Initial commit from Princess)
                $revision->task->update([
                    'status' => 'completed',
                    'completedAt' => now()
                ]);
<<<<<<< HEAD
                
                Log::info('Task marked as completed after revision', [
                    'task_id' => $revision->task->taskID,
                    'revision_id' => $revision->id
                ]);
            } elseif ($revision->source_type === 'project' && $revision->project) {
                // Check if all tasks in the project are completed
                $allTasksCompleted = $revision->project->tasks()
                    ->where('status', '!=', 'completed')
                    ->count() === 0;

                if ($allTasksCompleted) {
                    $revision->project->update([
                        'status' => 'review', // Set to review so client can check
                        'updated_at' => now()
                    ]);
                    
                    Log::info('Project set to review after revision completion', [
                        'project_id' => $revision->project->id,
                        'revision_id' => $revision->id
                    ]);
                } else {
                    Log::info('Project still has incomplete tasks after revision', [
                        'project_id' => $revision->project->id,
                        'revision_id' => $revision->id
                    ]);
                }
            }

            // Notify client that revision is completed
            $client = $revision->requestedBy;
            if ($client) {
                $client->notify(new RevisionCompletedNotification($revision));
                
                Log::info('Client notified of revision completion', [
=======
            }

            // Notify client
            $client = $revision->requestedBy;
            if ($client) {
                // You can create a RevisionCompletedNotification if needed
                Log::info('Revision completed, client should be notified', [
>>>>>>> 7c71488 (Initial commit from Princess)
                    'revision_id' => $revision->id,
                    'client_id' => $client->id
                ]);
            }

            DB::commit();

            Log::info('Revision marked as completed', [
                'revision_id' => $revision->id,
<<<<<<< HEAD
                'adiutor_id' => $adiutor->id,
                'source_type' => $revision->source_type
            ]);

            return redirect()->route('adiutor.revisions.show', $revision->id)
                ->with('success', 'Revision marked as completed successfully. Client has been notified.');
=======
                'adiutor_id' => $adiutor->id
            ]);

            return redirect()->route('adiutor.revisions.show', $revision->id)
                ->with('success', 'Revision marked as completed successfully.');
>>>>>>> 7c71488 (Initial commit from Princess)

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to complete revision', [
                'revision_id' => $id,
<<<<<<< HEAD
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
=======
                'error' => $e->getMessage()
>>>>>>> 7c71488 (Initial commit from Princess)
            ]);

            return redirect()->back()
                ->with('error', 'Failed to mark revision as completed. Please try again.');
        }
    }

    /**
     * Show the form to upload revised document
     */
    public function uploadForm($id)
    {
        $adiutor = Auth::user();
        
        $revision = RevisionRequest::with([
            'document',
            'task',
            'project'
        ])
        ->where('assigned_adiutor_id', $adiutor->id)
        ->where('status', 'approved')
        ->findOrFail($id);

        return view('adiutor.revisions.upload', compact('revision'));
    }
}
