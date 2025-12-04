<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Document;
use App\Services\CloudflareR2Service;
use App\Traits\ValidatesDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller for managing task deliverables.
 * 
 * Deliverables are now stored as documents with is_deliverable=true.
 * This simplifies the data model while maintaining the same functionality.
 */
class TaskDeliverableController extends Controller
{
    use ValidatesDocuments;

    /**
     * Display deliverables for a task.
     */
    public function index(Task $task)
    {
        $deliverables = $task->deliverables()->with('uploader', 'approver')->orderBy('created_at', 'desc')->get();
        $task->load(['project', 'assignedUser']);
        
        return view('admin.tasks.deliverables.index', [
            'task' => $task,
            'deliverables' => $deliverables,
            'stats' => $task->getDeliverablesCounts(),
        ]);
    }

    /**
     * Store a new deliverable for a task.
     */
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'type' => 'required|in:file,link',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required_if:type,file|file|max:' . $this->getMaxFileSize() . '|mimes:' . $this->getAllowedExtensions(),
            'link_url' => 'required_if:type,link|nullable|url|max:2048',
            'auto_approve' => 'nullable|boolean', // Admins can auto-approve their uploads
        ]);

        DB::beginTransaction();
        try {
            $autoApprove = $request->boolean('auto_approve', true); // Admins auto-approve by default

            if ($request->type === 'file' && $request->hasFile('file')) {
                // Handle file upload
                $file = $request->file('file');
                $r2Service = new CloudflareR2Service();
                
                $serviceRequestId = 'task-deliverable-' . $task->taskID;
                if ($task->project && $task->project->service_request_id) {
                    $serviceRequestId = $task->project->service_request_id;
                }
                
                $uploadResult = $r2Service->uploadDocument($file, $serviceRequestId, $task->taskID);
                
                if (!$uploadResult['success']) {
                    throw new \Exception('Failed to upload file: ' . $uploadResult['error']);
                }

                $document = Document::create([
                    'taskID' => $task->taskID,
                    'project_id' => $task->project_id,
                    'client_id' => $task->client_id,
                    'uploaded_by' => Auth::id(),
                    'fileName' => $request->title ?: $uploadResult['original_name'],
                    'filePath' => $uploadResult['url'],
                    'fileType' => $uploadResult['mime_type'],
                    'fileSize' => $uploadResult['size'],
                    'description' => $request->description,
                    'document_type' => 'deliverable',
                    'is_deliverable' => true,
                    'deliverable_type' => 'file',
                    'is_approved' => $autoApprove,
                    'approved_by' => $autoApprove ? Auth::id() : null,
                    'approved_at' => $autoApprove ? now() : null,
                    'uploadedAt' => now(),
                ]);
            } else {
                // Handle link
                $document = Document::create([
                    'taskID' => $task->taskID,
                    'project_id' => $task->project_id,
                    'client_id' => $task->client_id,
                    'uploaded_by' => Auth::id(),
                    'fileName' => $request->title,
                    'filePath' => null,
                    'fileType' => 'link',
                    'fileSize' => 0,
                    'description' => $request->description,
                    'link_url' => $request->link_url,
                    'document_type' => 'deliverable',
                    'is_deliverable' => true,
                    'deliverable_type' => 'link',
                    'is_approved' => $autoApprove,
                    'approved_by' => $autoApprove ? Auth::id() : null,
                    'approved_at' => $autoApprove ? now() : null,
                    'uploadedAt' => now(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Deliverable added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to add deliverable: ' . $e->getMessage()]);
        }
    }

    /**
     * Update a deliverable.
     */
    public function update(Request $request, Document $deliverable)
    {
        if (!$deliverable->is_deliverable) {
            abort(404, 'Deliverable not found.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link_url' => 'nullable|url|max:2048',
        ]);

        $updateData = [
            'fileName' => $request->title,
            'description' => $request->description,
        ];

        if ($deliverable->deliverable_type === 'link' && $request->has('link_url')) {
            $updateData['link_url'] = $request->link_url;
        }

        $deliverable->update($updateData);

        return redirect()->back()->with('success', 'Deliverable updated successfully.');
    }

    /**
     * Approve a deliverable.
     */
    public function approve(Document $deliverable)
    {
        if (!$deliverable->is_deliverable) {
            abort(404, 'Deliverable not found.');
        }

        $deliverable->approve(Auth::user());

        return redirect()->back()->with('success', 'Deliverable approved.');
    }

    /**
     * Reject a deliverable.
     */
    public function reject(Request $request, Document $deliverable)
    {
        if (!$deliverable->is_deliverable) {
            abort(404, 'Deliverable not found.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $deliverable->reject(Auth::id(), $request->reason);

        // Notify the uploader
        if ($deliverable->uploader) {
            $deliverable->uploader->notify(new \App\Notifications\DeliverableRejectedNotification($deliverable, $request->reason));
        }

        return redirect()->back()->with('success', 'Deliverable rejected.');
    }

    /**
     * Revoke approval of a deliverable.
     */
    public function revokeApproval(Request $request, Document $deliverable)
    {
        if (!$deliverable->is_deliverable || !$deliverable->is_approved) {
            abort(404, 'Approved deliverable not found.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $deliverable->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => 'Approval revoked: ' . $request->reason,
            'rejected_at' => now(),
        ]);

        // Notify the uploader
        if ($deliverable->uploader) {
            $deliverable->uploader->notify(new \App\Notifications\DeliverableApprovalRevokedNotification($deliverable, $request->reason));
        }

        return redirect()->back()->with('success', 'Deliverable approval revoked.');
    }

    /**
     * Delete a deliverable.
     */
    public function destroy(Document $deliverable)
    {
        if (!$deliverable->is_deliverable) {
            abort(404, 'Deliverable not found.');
        }

        $deliverable->delete();

        return redirect()->back()->with('success', 'Deliverable removed.');
    }

    /**
     * Check if task has deliverables (for AJAX).
     */
    public function checkDeliverables(Task $task)
    {
        return response()->json([
            'has_deliverables' => $task->hasDeliverables(),
            'has_approved_deliverables' => $task->hasApprovedDeliverables(),
            'counts' => $task->getDeliverablesCounts(),
        ]);
    }
}
