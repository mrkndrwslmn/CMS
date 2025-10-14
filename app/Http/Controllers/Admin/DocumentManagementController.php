<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use App\Models\Task;
use App\Models\Form;
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
        $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('documents', $fileName, 'public');
        
        $document = Document::create([
            'taskID' => $request->taskID,
            'fileName' => $fileName,
            'filePath' => $filePath,
            'fileType' => $file->getClientOriginalExtension(),
            'fileSize' => $file->getSize(),
        ]);
        
        return redirect()->route('admin.documents.index')
                        ->with('success', 'Document uploaded successfully.');
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
            // Delete old file
            if (Storage::disk('public')->exists($document->filePath)) {
                Storage::disk('public')->delete($document->filePath);
            }
            
            // Store new file
            $file = $request->file('document');
            $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents', $fileName, 'public');
            
            $updateData = array_merge($updateData, [
                'fileName' => $fileName,
                'filePath' => $filePath,
                'fileType' => $file->getClientOriginalExtension(),
                'fileSize' => $file->getSize(),
            ]);
        }
        
        $document->update($updateData);
        
        return redirect()->route('admin.documents.show', $document->documentID)
                        ->with('success', 'Document updated successfully.');
    }
    
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($document->filePath)) {
            Storage::disk('public')->delete($document->filePath);
        }
        
        $document->delete();
        
        return redirect()->route('admin.documents.index')
                        ->with('success', 'Document deleted successfully.');
    }
    
    public function download($id)
    {
        $document = Document::findOrFail($id);
        
        if (Storage::disk('public')->exists($document->filePath)) {
            $filePath = storage_path('app/public/' . $document->filePath);
            return response()->download($filePath, $document->fileName);
        }
        
        return redirect()->back()->with('error', 'File not found.');
    }
    
    public function preview($id)
    {
        $document = Document::findOrFail($id);
        
        if (Storage::disk('public')->exists($document->filePath)) {
            $filePath = storage_path('app/public/' . $document->filePath);
            
            return response()->file($filePath, [
                'Content-Disposition' => 'inline; filename="' . $document->fileName . '"'
            ]);
        }
        
        return redirect()->back()->with('error', 'File not found.');
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
                if (Storage::disk('public')->exists($document->filePath)) {
                    Storage::disk('public')->delete($document->filePath);
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
}