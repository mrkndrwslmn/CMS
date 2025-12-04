<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Document;
use App\Services\CloudflareR2Service;
use App\Traits\ValidatesDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller for adiutors managing task deliverables.
 * 
 * Deliverables are now stored as documents with is_deliverable=true.
 * This simplifies the data model while maintaining the same functionality.
 */
class TaskDeliverableController extends Controller
{
    use ValidatesDocuments;

    /**
     * Verify that the adiutor has access to the task.
     */
    protected function verifyTaskAccess(Task $task): bool
    {
        return $task->assignedTo == Auth::id() || 
               DB::table('project_assignments')
                   ->where('project_id', $task->project_id)
                   ->where('adiutor_id', Auth::id())
                   ->whereNotIn('status', ['removed', 'declined'])
                   ->exists();
    }

    /**
     * Get deliverables for a task.
     */
    public function index(Task $task)
    {
        if (!$this->verifyTaskAccess($task)) {
            abort(403, 'You do not have access to this task.');
        }

        $deliverables = $task->deliverables()->with('uploader')->orderBy('created_at', 'desc')->get();

        if (request()->ajax()) {
            return response()->json([
                'deliverables' => $deliverables,
                'stats' => $task->getDeliverablesCounts(),
            ]);
        }

        return view('adiutor.tasks.deliverables', [
            'task' => $task,
            'deliverables' => $deliverables,
            'stats' => $task->getDeliverablesCounts(),
        ]);
    }

    /**
     * Store a new deliverable (file or link).
     */
    public function store(Request $request, Task $task)
    {
        if (!$this->verifyTaskAccess($task)) {
            abort(403, 'You do not have access to this task.');
        }

        $request->validate([
            'type' => 'required|in:file,link',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required_if:type,file|file|max:' . $this->getMaxFileSize() . '|mimes:' . $this->getAllowedExtensions(),
            'link_url' => 'required_if:type,link|nullable|url|max:2048',
        ]);

        DB::beginTransaction();
        try {
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
                    'is_approved' => false, // Requires admin approval
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
                    'is_approved' => false, // Requires admin approval
                    'uploadedAt' => now(),
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'deliverable' => $document,
                    'stats' => $task->fresh()->getDeliverablesCounts(),
                    'message' => 'Deliverable added successfully. It will be visible to the client once approved.',
                ]);
            }

            return redirect()->back()->with('success', 'Deliverable added successfully. It will be visible to the client once approved.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add deliverable: ' . $e->getMessage(),
                ], 500);
            }
            
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

        if (!$deliverable->task || !$this->verifyTaskAccess($deliverable->task)) {
            abort(403, 'You do not have access to this task.');
        }

        // Only allow updating if the user uploaded it and it's not approved
        if ($deliverable->uploaded_by != Auth::id()) {
            abort(403, 'You can only edit deliverables you uploaded.');
        }

        if ($deliverable->is_approved) {
            abort(403, 'Cannot edit an approved deliverable.');
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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'deliverable' => $deliverable->fresh(),
            ]);
        }

        return redirect()->back()->with('success', 'Deliverable updated successfully.');
    }

    /**
     * Delete a deliverable.
     */
    public function destroy(Document $deliverable)
    {
        if (!$deliverable->is_deliverable) {
            abort(404, 'Deliverable not found.');
        }

        if (!$deliverable->task || !$this->verifyTaskAccess($deliverable->task)) {
            abort(403, 'You do not have access to this task.');
        }

        // Only allow deleting if the user uploaded it
        if ($deliverable->uploaded_by != Auth::id()) {
            abort(403, 'You can only delete deliverables you uploaded.');
        }

        // Don't allow deleting approved deliverables
        if ($deliverable->is_approved) {
            abort(403, 'Cannot delete an approved deliverable. Contact an admin to revoke approval first.');
        }

        $task = $deliverable->task;
        $deliverable->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'stats' => $task->fresh()->getDeliverablesCounts(),
            ]);
        }

        return redirect()->back()->with('success', 'Deliverable removed.');
    }

    /**
     * Check if task has deliverables.
     */
    public function check(Task $task)
    {
        if (!$this->verifyTaskAccess($task)) {
            abort(403, 'You do not have access to this task.');
        }

        return response()->json([
            'has_deliverables' => $task->hasDeliverables(),
            'has_approved_deliverables' => $task->hasApprovedDeliverables(),
            'counts' => $task->getDeliverablesCounts(),
        ]);
    }
}
