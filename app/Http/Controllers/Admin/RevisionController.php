<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RevisionRequest;
use App\Models\Task;
use App\Models\User;
use App\Notifications\RevisionApprovedNotification;
use App\Notifications\RevisionRejectedNotification;
use App\Mail\RevisionApproved;
use App\Mail\RevisionRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RevisionController extends Controller
{
    /**
     * Display a listing of revision requests
     */
    public function index(Request $request)
    {
        $query = RevisionRequest::with([
            'document',
            'requestedBy',
            'assignedAdiutor',
            'task',
            'project',
            'reviewedBy'
        ]);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by source type
        if ($request->has('source_type') && $request->source_type !== 'all') {
            $query->where('source_type', $request->source_type);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                  ->orWhereHas('document', function($docQuery) use ($search) {
                      $docQuery->where('fileName', 'like', "%{$search}%");
                  })
                  ->orWhereHas('requestedBy', function($userQuery) use ($search) {
                      $userQuery->where('fullName', 'like', "%{$search}%");
                  });
            });
        }

        $revisions = $query->latest()->paginate(15);

        // Get counts for tabs
        $statusCounts = [
            'all' => RevisionRequest::count(),
            'pending' => RevisionRequest::where('status', 'pending')->count(),
            'approved' => RevisionRequest::where('status', 'approved')->count(),
            'rejected' => RevisionRequest::where('status', 'rejected')->count(),
            'completed' => RevisionRequest::where('status', 'completed')->count(),
        ];

        return view('admin.revisions.index', compact('revisions', 'statusCounts'));
    }

    /**
     * Display the specified revision request
     */
    public function show($id)
    {
        $revision = RevisionRequest::with([
            'document.uploader',
            'requestedBy.clientProfile',
            'assignedAdiutor.adiutorProfile',
            'task.project',
            'project.serviceRequest',
            'serviceRequest',
            'reviewedBy',
            'completedBy'
        ])->findOrFail($id);

        return view('admin.revisions.show', compact('revision'));
    }

    /**
     * Approve a revision request
     */
    public function approve(Request $request, $id)
    {
        $admin = Auth::user();
        
        $revision = RevisionRequest::with([
            'document',
            'task',
            'assignedAdiutor',
            'requestedBy'
        ])->findOrFail($id);

        // Validate that it's still pending
        if ($revision->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This revision request has already been reviewed.');
        }

        // Validate input
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
            'assigned_adiutor_id' => 'nullable|exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            // Update adiutor if specified
            $adiutorId = $validated['assigned_adiutor_id'] ?? $revision->assigned_adiutor_id;

            // Update revision request
            $revision->update([
                'status' => 'approved',
                'reviewed_by' => $admin->id,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'reviewed_at' => now(),
                'assigned_adiutor_id' => $adiutorId
            ]);

            // If it's a task-based revision, reopen the task
            if ($revision->isTaskBased() && $revision->task) {
                $this->reopenTask($revision->task);
            }

            // Notify the adiutor
            if ($adiutorId) {
                $adiutor = User::find($adiutorId);
                if ($adiutor) {
                    // Send notification
                    $adiutor->notify(new RevisionApprovedNotification($revision));
                    
                    // Send email
                    try {
                        Mail::to($adiutor->email)->send(new RevisionApproved($revision));
                    } catch (\Exception $e) {
                        Log::error('Failed to send revision approval email', [
                            'adiutor_id' => $adiutor->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            // Also notify the client
            $client = $revision->requestedBy;
            if ($client) {
                $client->notify(new RevisionApprovedNotification($revision));
            }

            DB::commit();

            Log::info('Revision request approved', [
                'revision_id' => $revision->id,
                'admin_id' => $admin->id,
                'adiutor_id' => $adiutorId,
                'task_reopened' => $revision->isTaskBased()
            ]);

            return redirect()->route('admin.revisions.show', $revision->id)
                ->with('success', 'Revision request approved successfully. The adiutor has been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to approve revision request', [
                'revision_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to approve revision request. Please try again.');
        }
    }

    /**
     * Reject a revision request
     */
    public function reject(Request $request, $id)
    {
        $admin = Auth::user();
        
        $revision = RevisionRequest::with([
            'requestedBy'
        ])->findOrFail($id);

        // Validate that it's still pending
        if ($revision->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This revision request has already been reviewed.');
        }

        // Validate input
        $validated = $request->validate([
            'admin_notes' => 'required|string|min:10|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Update revision request
            $revision->update([
                'status' => 'rejected',
                'reviewed_by' => $admin->id,
                'admin_notes' => $validated['admin_notes'],
                'reviewed_at' => now()
            ]);

            // Notify the client
            $client = $revision->requestedBy;
            if ($client) {
                $client->notify(new RevisionRejectedNotification($revision));
                
                // Send email
                try {
                    Mail::to($client->email)->send(new RevisionRejected($revision));
                } catch (\Exception $e) {
                    Log::error('Failed to send revision rejection email', [
                        'client_id' => $client->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            Log::info('Revision request rejected', [
                'revision_id' => $revision->id,
                'admin_id' => $admin->id
            ]);

            return redirect()->route('admin.revisions.show', $revision->id)
                ->with('success', 'Revision request rejected. The client has been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to reject revision request', [
                'revision_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to reject revision request. Please try again.');
        }
    }

    /**
     * Reopen a task when revision is approved
     */
    protected function reopenTask(Task $task)
    {
        // Only reopen if task was completed
        if ($task->status === 'completed') {
            $task->update([
                'status' => 'in_progress',
                'updated_at' => now()
            ]);

            Log::info('Task reopened for revision', [
                'task_id' => $task->taskID,
                'previous_status' => 'completed'
            ]);
        }
    }

    /**
     * Reassign revision to different adiutor
     */
    public function reassign(Request $request, $id)
    {
        $admin = Auth::user();
        
        $revision = RevisionRequest::findOrFail($id);

        $validated = $request->validate([
            'assigned_adiutor_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldAdiutorId = $revision->assigned_adiutor_id;
        $newAdiutorId = $validated['assigned_adiutor_id'];

        $revision->update([
            'assigned_adiutor_id' => $newAdiutorId,
            'admin_notes' => $validated['notes'] ?? $revision->admin_notes
        ]);

        // Notify new adiutor
        $newAdiutor = User::find($newAdiutorId);
        if ($newAdiutor && $revision->isApproved()) {
            $newAdiutor->notify(new RevisionApprovedNotification($revision));
        }

        Log::info('Revision reassigned', [
            'revision_id' => $revision->id,
            'old_adiutor_id' => $oldAdiutorId,
            'new_adiutor_id' => $newAdiutorId,
            'admin_id' => $admin->id
        ]);

        return redirect()->back()
            ->with('success', 'Revision request reassigned successfully.');
    }
}
