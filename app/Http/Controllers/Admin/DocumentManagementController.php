<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\User;
use App\Models\Task;
use App\Models\Form;
use App\Services\CloudflareR2Service;
use App\Traits\ValidatesDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentManagementController extends Controller
{
    use ValidatesDocuments;
    public function index(Request $request)
    {
        $query = Document::with(['task', 'client', 'project']);
        
        // Search functionality - full-text search across multiple fields
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fileName', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('document_type', 'like', "%{$search}%")
                  ->orWhere('fileType', 'like', "%{$search}%")
                  ->orWhereHas('task', function($taskQuery) use ($search) {
                      $taskQuery->where('taskTitle', 'like', "%{$search}%");
                  })
                  ->orWhereHas('client', function($clientQuery) use ($search) {
                      $clientQuery->where('fullName', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('project', function($projectQuery) use ($search) {
                      $projectQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        // Type filter
        if ($request->filled('type')) {
            $query->where('fileType', $request->type);
        }
        
        // This filter is commented out because 'category' column doesn't exist in the documents table
        // if ($request->filled('category')) {
        //     $query->where('category', $request->category);
        // }
        
        // Task filter
        if ($request->filled('task')) {
            $query->where('taskID', $request->task);
        }
        
        // Client filter
        if ($request->filled('client')) {
            $query->where('client_id', $request->client);
        }
        
        // Project filter
        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        $documents = $query->paginate(15)->withQueryString();
        
        // Get filter options
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $adiutors = User::where('role', 'adiutor')->orderBy('fullName')->get();
        $projects = \App\Models\Project::orderBy('title')->get();
        
        // Get statistics (cached for 5 minutes to reduce DB load)
        $stats = Cache::remember('documents.stats', 300, function () {
            return [
                'total_documents' => Document::count(),
                'total_size' => Document::sum('fileSize'),
                'this_month' => Document::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->count(),
                'by_type' => Document::selectRaw('fileType, count(*) as count')
                                     ->groupBy('fileType')
                                     ->pluck('count', 'fileType')
                                     ->toArray(),
            ];
        });
        
        return view('admin.documents.index', compact('documents', 'clients', 'adiutors', 'projects', 'stats'));
    }
    
    public function show($id)
    {
        $document = Document::with(['task'])->findOrFail($id);
        
        return view('admin.documents.show', compact('document'));
    }
    
    public function create()
    {
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $adiutors = User::where('role', 'adiutor')->orderBy('fullName')->get();
        $tasks = Task::with('client')->orderBy('created_at', 'desc')->take(50)->get();
        $forms = Form::with('client')->orderBy('created_at', 'desc')->take(50)->get();
        
        return view('admin.documents.create', compact('clients', 'adiutors', 'tasks', 'forms'));
    }
    
    public function store(Request $request)
    {
        $rules = array_merge($this->getDocumentValidationRules(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'taskID' => 'nullable|exists:tasks,taskID',
            'category' => 'nullable|string|in:report,contract,invoice,proposal,presentation,code,design,documentation,other',
            'isPrivate' => 'nullable|boolean',
        ]);
        
        $request->validate($rules);
        
        $file = $request->file('document');
        $r2Service = new CloudflareR2Service();
        
        // Determine service request ID for proper organization
        $serviceRequestId = 'admin-upload';
        if ($request->taskID) {
            $task = Task::find($request->taskID);
            if ($task && $task->project && $task->project->service_request_id) {
                $serviceRequestId = $task->project->service_request_id;
            }
        }
        
        $uploadResult = $r2Service->uploadDocument($file, $serviceRequestId);
        
        if ($uploadResult['success']) {
            $document = Document::create([
                'taskID' => $request->taskID,
                'fileName' => $uploadResult['original_name'],
                'filePath' => $uploadResult['url'], // Store R2 URL
                'fileType' => $uploadResult['mime_type'],
                'fileSize' => $uploadResult['size'],
                'description' => $request->description,
                'document_type' => $request->category, // Map category to document_type column
                'is_public' => !$request->boolean('isPrivate'), // Invert isPrivate to is_public
                'uploaded_by' => Auth::id(),
                'uploadedAt' => now(),
            ]);
            
            // Invalidate stats cache
            Cache::forget('documents.stats');
            
            // Audit log: document uploaded
            AuditLog::logAction($document, 'uploaded', null, [
                'fileName' => $document->fileName,
                'fileType' => $document->fileType,
                'fileSize' => $document->fileSize,
                'taskID' => $document->taskID,
            ], 'document_management', [
                'storage' => 'cloudflare_r2',
                'service_request_id' => $serviceRequestId,
            ]);
            
            return redirect()->route('admin.documents.index')
                            ->with('success', 'Document uploaded successfully to cloud storage.');
        } else {
            return redirect()->back()
                            ->withErrors(['document' => 'Failed to upload document: ' . $uploadResult['error']]);
        }
    }
    
    /**
     * Show the bulk upload form.
     */
    public function bulkCreate()
    {
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $tasks = Task::with('client')->orderBy('created_at', 'desc')->take(50)->get();
        $projects = \App\Models\Project::orderBy('title')->get();
        
        return view('admin.documents.bulk-create', compact('clients', 'tasks', 'projects'));
    }
    
    /**
     * Handle bulk document upload.
     */
    public function bulkStore(Request $request)
    {
        $rules = array_merge($this->getBulkDocumentValidationRules(), [
            'taskID' => 'nullable|exists:tasks,taskID',
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'nullable|exists:users,id',
            'document_type' => 'nullable|string|in:report,contract,invoice,proposal,presentation,deliverable,code,design,documentation,other',
            'is_public' => 'nullable|boolean',
        ]);
        
        $request->validate($rules);
        
        $files = $request->file('documents');
        $r2Service = new CloudflareR2Service();
        
        // Determine service request ID for proper organization
        $serviceRequestId = 'admin-bulk-upload';
        if ($request->taskID) {
            $task = Task::find($request->taskID);
            if ($task && $task->project && $task->project->service_request_id) {
                $serviceRequestId = $task->project->service_request_id;
            }
        } elseif ($request->project_id) {
            $project = \App\Models\Project::find($request->project_id);
            if ($project && $project->service_request_id) {
                $serviceRequestId = $project->service_request_id;
            }
        }
        
        $successCount = 0;
        $failedFiles = [];
        $uploadedDocuments = [];
        
        foreach ($files as $file) {
            try {
                $uploadResult = $r2Service->uploadDocument($file, $serviceRequestId);
                
                if ($uploadResult['success']) {
                    $document = Document::create([
                        'taskID' => $request->taskID,
                        'project_id' => $request->project_id,
                        'client_id' => $request->client_id,
                        'fileName' => $uploadResult['original_name'],
                        'filePath' => $uploadResult['url'],
                        'fileType' => $uploadResult['mime_type'],
                        'fileSize' => $uploadResult['size'],
                        'document_type' => $request->document_type,
                        'is_public' => $request->boolean('is_public', true),
                        'uploaded_by' => Auth::id(),
                        'uploadedAt' => now(),
                    ]);
                    
                    $uploadedDocuments[] = $document;
                    $successCount++;
                } else {
                    $failedFiles[] = $file->getClientOriginalName() . ' (' . $uploadResult['error'] . ')';
                }
            } catch (\Exception $e) {
                $failedFiles[] = $file->getClientOriginalName() . ' (' . $e->getMessage() . ')';
            }
        }
        
        // Invalidate stats cache
        Cache::forget('documents.stats');
        
        // Audit log: bulk upload
        if ($successCount > 0) {
            AuditLog::logAction(null, 'bulk_uploaded', null, [
                'count' => $successCount,
                'failed_count' => count($failedFiles),
                'taskID' => $request->taskID,
                'project_id' => $request->project_id,
                'client_id' => $request->client_id,
            ], 'document_management', [
                'storage' => 'cloudflare_r2',
                'service_request_id' => $serviceRequestId,
                'uploaded_files' => array_map(fn($d) => $d->fileName, $uploadedDocuments),
            ]);
        }
        
        // Build response message
        if ($successCount === count($files)) {
            return redirect()->route('admin.documents.index')
                            ->with('success', "Successfully uploaded {$successCount} document(s) to cloud storage.");
        } elseif ($successCount > 0) {
            return redirect()->route('admin.documents.index')
                            ->with('warning', "Uploaded {$successCount} document(s). Failed: " . implode(', ', $failedFiles));
        } else {
            return redirect()->back()
                            ->withErrors(['documents' => 'Failed to upload documents: ' . implode(', ', $failedFiles)]);
        }
    }
    
    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $adiutors = User::where('role', 'adiutor')->orderBy('fullName')->get();
        $tasks = Task::with('client')->orderBy('created_at', 'desc')->take(50)->get();
        $forms = Form::with('client')->orderBy('created_at', 'desc')->take(50)->get();
        
        return view('admin.documents.edit', compact('document', 'clients', 'adiutors', 'tasks', 'forms'));
    }
    
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        
        $rules = array_merge($this->getDocumentValidationRules(false), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'taskID' => 'nullable|exists:tasks,taskID',
            'category' => 'nullable|string|in:report,contract,invoice,proposal,presentation,code,design,documentation,other',
            'isPrivate' => 'nullable|boolean',
        ]);
        
        $request->validate($rules);
        
        // Capture old values for audit
        $oldValues = $document->only(['fileName', 'fileType', 'fileSize', 'description', 'document_type', 'is_public', 'taskID', 'version']);
        
        // Handle file replacement with versioning
        if ($request->hasFile('document')) {
            $r2Service = new CloudflareR2Service();
            $file = $request->file('document');
            
            // Determine service request ID for proper organization
            $serviceRequestId = 'admin-upload';
            if ($request->taskID ?? $document->taskID) {
                $taskId = $request->taskID ?? $document->taskID;
                $task = Task::find($taskId);
                if ($task && $task->project && $task->project->service_request_id) {
                    $serviceRequestId = $task->project->service_request_id;
                }
            }
            
            $uploadResult = $r2Service->uploadDocument($file, $serviceRequestId);
            
            if ($uploadResult['success']) {
                // Create a new version instead of overwriting
                $originalDocumentId = $document->original_document_id ?? $document->documentID;
                $newVersion = $document->version + 1;
                
                // Create new document as new version
                $newDocument = Document::create([
                    'taskID' => $request->taskID ?? $document->taskID,
                    'service_request_id' => $document->service_request_id,
                    'project_id' => $document->project_id,
                    'client_id' => $document->client_id,
                    'documentable_type' => $document->documentable_type,
                    'documentable_id' => $document->documentable_id,
                    'fileName' => $uploadResult['original_name'],
                    'filePath' => $uploadResult['url'],
                    'fileType' => $uploadResult['mime_type'],
                    'fileSize' => $uploadResult['size'],
                    'version' => $newVersion,
                    'parent_document_id' => $document->documentID,
                    'original_document_id' => $originalDocumentId,
                    'document_type' => $request->category ?? $document->document_type,
                    'description' => $request->description ?? $document->description,
                    'is_public' => $request->has('isPrivate') ? !$request->boolean('isPrivate') : $document->is_public,
                    'uploaded_by' => Auth::id(),
                    'uploadedAt' => now(),
                ]);
                
                // Invalidate stats cache
                Cache::forget('documents.stats');
                
                // Audit log: new version created
                AuditLog::logAction($newDocument, 'version_created', $oldValues, [
                    'fileName' => $newDocument->fileName,
                    'fileType' => $newDocument->fileType,
                    'fileSize' => $newDocument->fileSize,
                    'version' => $newVersion,
                    'previous_version' => $document->version,
                ], 'document_management', [
                    'parent_document_id' => $document->documentID,
                    'original_document_id' => $originalDocumentId,
                ]);
                
                return redirect()->route('admin.documents.show', $newDocument->documentID)
                                ->with('success', "Document updated successfully. New version {$newVersion} created.");
            } else {
                return redirect()->back()
                                ->withErrors(['document' => 'Failed to upload document: ' . $uploadResult['error']]);
            }
        }
        
        // No file replacement - just update metadata
        $updateData = [
            'description' => $request->description,
            'document_type' => $request->category,
            'is_public' => !$request->boolean('isPrivate'),
        ];
        
        // Update task association
        if ($request->has('taskID')) {
            $updateData['taskID'] = $request->taskID;
        }
        
        $document->update($updateData);
        
        // Invalidate stats cache (file size/type may have changed)
        Cache::forget('documents.stats');
        
        // Audit log: document metadata updated
        AuditLog::logAction($document, 'updated', $oldValues, $updateData, 'document_management', [
            'file_replaced' => false,
        ]);
        
        return redirect()->route('admin.documents.show', $document->documentID)
                        ->with('success', 'Document updated successfully.');
    }
    
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        // Capture document info for audit before soft deletion
        $documentInfo = [
            'documentID' => $document->documentID,
            'fileName' => $document->fileName,
            'fileType' => $document->fileType,
            'fileSize' => $document->fileSize,
            'filePath' => $document->filePath,
            'taskID' => $document->taskID,
        ];
        
        // Soft delete (don't delete actual file - it can be restored)
        $document->delete();
        
        // Invalidate stats cache
        Cache::forget('documents.stats');
        
        // Audit log: document soft deleted
        AuditLog::logSensitiveAction('document_deleted', array_merge($documentInfo, [
            'deletion_type' => 'soft',
        ]));
        
        return redirect()->route('admin.documents.index')
                        ->with('success', 'Document moved to trash. It can be restored within 30 days.');
    }
    
    /**
     * Display trashed documents
     */
    public function trash(Request $request)
    {
        $query = Document::onlyTrashed()->with(['task']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fileName', 'like', "%{$search}%")
                  ->orWhere('filePath', 'like', "%{$search}%");
            });
        }
        
        $documents = $query->orderBy('deleted_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.documents.trash', compact('documents'));
    }
    
    /**
     * Restore a soft-deleted document
     */
    public function restore($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);
        
        $document->restore();
        
        // Invalidate stats cache
        Cache::forget('documents.stats');
        
        // Audit log: document restored
        AuditLog::logAction($document, 'restored', null, [
            'fileName' => $document->fileName,
            'documentID' => $document->documentID,
        ], 'document_management');
        
        return redirect()->route('admin.documents.show', $document->documentID)
                        ->with('success', 'Document restored successfully.');
    }
    
    /**
     * Permanently delete a document (force delete)
     */
    public function forceDelete($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);
        
        // Capture document info for audit before permanent deletion
        $documentInfo = [
            'documentID' => $document->documentID,
            'fileName' => $document->fileName,
            'fileType' => $document->fileType,
            'fileSize' => $document->fileSize,
            'filePath' => $document->filePath,
            'taskID' => $document->taskID,
        ];
        
        // Now actually delete the file from storage
        if ($document->isR2File()) {
            $r2Service = new CloudflareR2Service();
            $deleted = $r2Service->deleteFileByUrl($document->filePath);
            
            if ($deleted) {
                \Log::info('Permanently deleted R2 document', ['path' => $document->filePath]);
            } else {
                \Log::warning('Failed to delete R2 document (file may not exist)', ['path' => $document->filePath]);
            }
        } else {
            if (Storage::disk('public')->exists($document->filePath)) {
                Storage::disk('public')->delete($document->filePath);
            }
        }
        
        // Permanently delete from database
        $document->forceDelete();
        
        // Audit log: document permanently deleted
        AuditLog::logSensitiveAction('document_permanently_deleted', $documentInfo);
        
        return redirect()->route('admin.documents.trash')
                        ->with('success', 'Document permanently deleted.');
    }
    
    /**
     * Restore multiple documents from trash
     */
    public function bulkRestore(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:documents,documentID',
        ]);
        
        $restoredCount = Document::onlyTrashed()
            ->whereIn('documentID', $request->document_ids)
            ->restore();
        
        // Invalidate stats cache
        Cache::forget('documents.stats');
        
        // Audit log: bulk restore
        AuditLog::logSensitiveAction('documents_bulk_restored', [
            'document_ids' => $request->document_ids,
            'restored_count' => $restoredCount,
        ]);
        
        return redirect()->back()
                        ->with('success', "{$restoredCount} document(s) restored successfully.");
    }
    
    /**
     * Empty the trash (permanently delete all trashed documents)
     */
    public function emptyTrash()
    {
        $trashedDocuments = Document::onlyTrashed()->get();
        $deletedCount = 0;
        $r2Service = new CloudflareR2Service();
        
        foreach ($trashedDocuments as $document) {
            // Delete file from storage
            if ($document->isR2File()) {
                $r2Service->deleteFileByUrl($document->filePath);
            } else {
                if (Storage::disk('public')->exists($document->filePath)) {
                    Storage::disk('public')->delete($document->filePath);
                }
            }
            
            $document->forceDelete();
            $deletedCount++;
        }
        
        // Audit log: trash emptied
        AuditLog::logSensitiveAction('documents_trash_emptied', [
            'deleted_count' => $deletedCount,
        ]);
        
        return redirect()->route('admin.documents.trash')
                        ->with('success', "Trash emptied. {$deletedCount} document(s) permanently deleted.");
    }
    
    public function download($id)
    {
        $document = Document::findOrFail($id);
        
        // Audit log: document downloaded
        AuditLog::logAction($document, 'downloaded', null, null, 'document_access', [
            'fileName' => $document->fileName,
            'fileSize' => $document->fileSize,
        ]);
        
        // Use the model's method to get the proper download URL
        return redirect($document->getDownloadUrl());
    }
    
    public function preview($id)
    {
        $document = Document::findOrFail($id);
        
        // Use the model's method to get the proper display URL
        return redirect($document->getDisplayUrl());
    }
    
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:documents,documentID',
        ]);
        
        $documents = Document::whereIn('documentID', $request->document_ids);
        
        if ($request->action === 'delete') {
            $documentsToDelete = $documents->get();
            $r2Service = new CloudflareR2Service();
            $deletedCount = 0;
            $failedCount = 0;
            
            foreach ($documentsToDelete as $document) {
                // Check if this is an R2 file
                if ($document->isR2File()) {
                    // Actually delete the R2 file
                    $deleted = $r2Service->deleteFileByUrl($document->filePath);
                    if ($deleted) {
                        $deletedCount++;
                        \Log::info('Bulk deleted R2 document', ['path' => $document->filePath]);
                    } else {
                        $failedCount++;
                        \Log::warning('Bulk delete: failed to delete R2 document', ['path' => $document->filePath]);
                    }
                } else {
                    // Legacy local file
                    if (Storage::disk('public')->exists($document->filePath)) {
                        Storage::disk('public')->delete($document->filePath);
                        $deletedCount++;
                    }
                }
            }
            $documents->delete();
            
            // Invalidate stats cache
            Cache::forget('documents.stats');
            
            // Audit log: bulk delete
            AuditLog::logSensitiveAction('documents_bulk_deleted', [
                'document_ids' => $request->document_ids,
                'total_count' => count($request->document_ids),
                'files_deleted' => $deletedCount,
                'files_failed' => $failedCount,
            ]);
            
            $message = "Documents deleted successfully. Files removed: {$deletedCount}";
            if ($failedCount > 0) {
                $message .= " (Failed to remove {$failedCount} files from storage)";
            }
        } else {
            $message = 'Invalid action.';
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3',
        ]);
        
        $query = $request->q;
        
        $documents = Document::with(['task'])
            ->where(function($q) use ($query) {
                $q->where('fileName', 'like', "%{$query}%")
                  ->orWhere('filePath', 'like', "%{$query}%")
                  ->orWhere('fileType', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.documents.search', compact('documents', 'query'));
    }

    /**
     * Upload document to project (not tied to task)
     */
    public function uploadToProject(Request $request, $projectId)
    {
        $rules = array_merge($this->getDocumentValidationRules(true, 'file'), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'document_type' => 'required|in:contract,deliverable,requirement,code,design,documentation,other',
        ]);
        
        $request->validate($rules);
        
        $project = \App\Models\Project::findOrFail($projectId);
        
        // Upload file to R2
        $file = $request->file('file');
        $r2Service = new CloudflareR2Service();
        
        $serviceRequestId = $project->service_request_id ?? $projectId;
        $uploadResult = $r2Service->uploadDocument($file, $serviceRequestId);
        
        if ($uploadResult['success']) {
            $document = Document::create([
                'project_id' => $projectId,
                'taskID' => null, // Project-level document
                'client_id' => $project->client_id,
                'uploaded_by' => Auth::id(),
                'fileName' => $uploadResult['original_name'],
                'filePath' => $uploadResult['url'], // Store R2 URL
                'fileType' => $uploadResult['mime_type'],
                'fileSize' => $uploadResult['size'],
                'document_type' => $request->document_type,
                'description' => $request->description,
                'uploadedAt' => now(),
            ]);
            
            // Invalidate stats cache
            Cache::forget('documents.stats');
            
            // Audit log: project document uploaded
            AuditLog::logAction($document, 'uploaded', null, [
                'fileName' => $document->fileName,
                'fileType' => $document->fileType,
                'fileSize' => $document->fileSize,
                'project_id' => $projectId,
                'document_type' => $document->document_type,
            ], 'document_management', [
                'storage' => 'cloudflare_r2',
                'upload_type' => 'project_document',
                'service_request_id' => $serviceRequestId,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully to cloud storage.',
                'document' => $document
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $uploadResult['error']
            ], 500);
        }
    }

    /**
     * Get pending deliverables for approval
     */
    public function pendingDeliverables(Request $request)
    {
        $query = Document::with(['task', 'uploader', 'project'])
            ->where('is_deliverable', true)
            ->where('is_approved', false)
            ->where('is_archived', false);
        
        // Filter by task
        if ($request->filled('task_id')) {
            $query->where('taskID', $request->task_id);
        }
        
        // Filter by project
        if ($request->filled('project_id')) {
            $query->whereHas('task', function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }
        
        $deliverables = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
        
        return view('admin.documents.pending-deliverables', compact('deliverables'));
    }

    /**
     * Approve a deliverable
     */
    public function approveDeliverable(Request $request, $documentId)
    {
        $document = Document::where('is_deliverable', true)
            ->findOrFail($documentId);
        
        if ($document->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'This deliverable has already been approved.'
            ], 400);
        }
        
        $document->approve(Auth::id());
        
        // Log the approval
        AuditLog::logAction($document, 'approved', null, [
            'document_id' => $document->documentID,
            'fileName' => $document->fileName,
            'task_id' => $document->taskID,
            'approved_by' => Auth::id(),
        ], 'deliverable_approval', [
            'approval_type' => 'admin_approval',
        ]);
        
        // Notify the adiutor that their deliverable was approved
        if ($document->uploader) {
            $document->uploader->notify(new \App\Notifications\DeliverableApprovedNotification($document));
        }
        
        // Notify the client that a new deliverable is available
        $client = $document->task?->client;
        if ($client) {
            $client->notify(new \App\Notifications\NewDeliverableAvailableNotification($document));
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Deliverable approved successfully. The client can now view it.',
            ]);
        }
        
        return redirect()->back()->with('success', 'Deliverable approved successfully. The client can now view it.');
    }

    /**
     * Reject a deliverable
     */
    public function rejectDeliverable(Request $request, $documentId)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);
        
        $document = Document::where('is_deliverable', true)
            ->findOrFail($documentId);
        
        if ($document->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reject an already approved deliverable.'
            ], 400);
        }
        
        $document->reject(Auth::id(), $request->reason);
        
        // Log the rejection
        AuditLog::logAction($document, 'rejected', null, [
            'document_id' => $document->documentID,
            'fileName' => $document->fileName,
            'task_id' => $document->taskID,
            'rejected_by' => Auth::id(),
            'rejection_reason' => $request->reason,
        ], 'deliverable_approval', [
            'approval_type' => 'admin_rejection',
        ]);
        
        // Notify the adiutor that their deliverable was rejected
        if ($document->uploader) {
            $document->uploader->notify(new \App\Notifications\DeliverableRejectedNotification($document, $request->reason));
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Deliverable rejected. The adiutor has been notified.',
            ]);
        }
        
        return redirect()->back()->with('success', 'Deliverable rejected. The adiutor has been notified.');
    }

    /**
     * Revoke approval for a deliverable
     */
    public function revokeApproval(Request $request, $documentId)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);
        
        $document = Document::where('is_deliverable', true)
            ->where('is_approved', true)
            ->findOrFail($documentId);
        
        $previousApprover = $document->approved_by;
        $previousApprovalDate = $document->approved_at;
        
        $document->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => 'Approval revoked: ' . $request->reason,
            'rejected_at' => now(),
        ]);
        
        // Log the revocation
        AuditLog::logAction($document, 'approval_revoked', [
            'was_approved_by' => $previousApprover,
            'was_approved_at' => $previousApprovalDate,
        ], [
            'revoked_by' => Auth::id(),
            'revocation_reason' => $request->reason,
        ], 'deliverable_approval', [
            'approval_type' => 'admin_revocation',
        ]);
        
        // Notify the adiutor
        if ($document->uploader) {
            $document->uploader->notify(new \App\Notifications\DeliverableApprovalRevokedNotification($document, $request->reason));
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Approval has been revoked. The deliverable is no longer visible to the client.',
            ]);
        }
        
        return redirect()->back()->with('success', 'Approval has been revoked.');
    }
}
