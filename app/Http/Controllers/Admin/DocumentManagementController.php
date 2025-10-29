<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use App\Models\Task;
use App\Models\Form;
use App\Services\CloudflareR2Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['task']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fileName', 'like', "%{$search}%")
                  ->orWhere('filePath', 'like', "%{$search}%")
                  ->orWhere('fileType', 'like', "%{$search}%")
                  ->orWhereHas('task', function($taskQuery) use ($search) {
                      $taskQuery->where('taskTitle', 'like', "%{$search}%");
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
        
        // Get statistics
        $stats = [
            'total_documents' => Document::count(),
            'total_size' => Document::sum('fileSize'),
            'this_month' => Document::whereMonth('created_at', now()->month)->count(),
            'by_type' => Document::selectRaw('fileType, count(*) as count')->groupBy('fileType')->pluck('count', 'fileType'),
        ];
        
        return view('admin.documents.index', compact('documents', 'clients', 'adiutors', 'stats'));
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|max:10240', // 10MB max
            'taskID' => 'nullable|exists:tasks,taskID',
            'category' => 'nullable|string',
            'isPrivate' => 'nullable|boolean',
        ]);
        
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
                'uploaded_by' => Auth::id(),
                'uploadedAt' => now(),
            ]);
            
            return redirect()->route('admin.documents.index')
                            ->with('success', 'Document uploaded successfully to cloud storage.');
        } else {
            return redirect()->back()
                            ->withErrors(['document' => 'Failed to upload document: ' . $uploadResult['error']]);
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
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'nullable|file|max:10240', // 10MB max
            'taskID' => 'nullable|exists:tasks,taskID',
            'category' => 'nullable|string',
            'isPrivate' => 'nullable|boolean',
        ]);
        
        // Initialize update data
        $updateData = [];
        
        // Update task association
        if ($request->has('taskID')) {
            $updateData['taskID'] = $request->taskID;
        }
        
        // Handle file replacement
        if ($request->hasFile('document')) {
            $r2Service = new CloudflareR2Service();
            
            // Delete old file if it's in R2
            if ($document->filePath && (str_starts_with($document->filePath, 'https://') || str_starts_with($document->filePath, 'http://'))) {
                // Old file is in R2 - we could delete it, but for safety we'll keep it for now
                // $r2Service->deleteFile($oldR2Path);
            } elseif ($document->filePath && Storage::disk('public')->exists($document->filePath)) {
                // Old file is local - delete it
                Storage::disk('public')->delete($document->filePath);
            }
            
            // Upload new file to R2
            $file = $request->file('document');
            
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
                $updateData = array_merge($updateData, [
                    'fileName' => $uploadResult['original_name'],
                    'filePath' => $uploadResult['url'], // Store R2 URL
                    'fileType' => $uploadResult['mime_type'],
                    'fileSize' => $uploadResult['size'],
                ]);
            } else {
                return redirect()->back()
                                ->withErrors(['document' => 'Failed to upload document: ' . $uploadResult['error']]);
            }
        }
        
        $document->update($updateData);
        
        return redirect()->route('admin.documents.show', $document->documentID)
                        ->with('success', 'Document updated successfully.');
    }
    
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        // Delete file from storage
        if ($document->isR2File()) {
            // For R2 files, we rely on the R2 service for deletion if needed
            // R2 files are managed by Cloudflare, so we just remove the database record
            \Log::info('Deleting R2 document: ' . $document->filePath);
        } else {
            // Delete legacy local files
            if (Storage::disk('public')->exists($document->filePath)) {
                Storage::disk('public')->delete($document->filePath);
            }
        }
        
        $document->delete();
        
        return redirect()->route('admin.documents.index')
                        ->with('success', 'Document deleted successfully.');
    }
    
    public function download($id)
    {
        $document = Document::findOrFail($id);
        
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
            foreach ($documentsToDelete as $document) {
                // Check if this is an R2 file
                if ($document->filePath && (str_starts_with($document->filePath, 'https://') || str_starts_with($document->filePath, 'http://'))) {
                    // R2 file - just log the deletion
                    \Log::info('Bulk deleting R2 document: ' . $document->filePath);
                } else {
                    // Legacy local file
                    if (Storage::disk('public')->exists($document->filePath)) {
                        Storage::disk('public')->delete($document->filePath);
                    }
                }
            }
            $documents->delete();
            $message = 'Documents deleted successfully.';
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
        $request->validate([
            'file' => 'required|file|max:20480', // 20MB
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'document_type' => 'required|in:contract,deliverable,requirement,other',
        ]);
        
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
        
        return redirect()->back()
            ->with('success', 'Document uploaded successfully.');
    }
}
