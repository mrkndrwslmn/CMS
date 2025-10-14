<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Task;
use App\Models\Form;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClientManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'client')->with(['tasks', 'forms']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fullName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phoneNumber', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        $clients = $query->paginate(15)->withQueryString();
        
        // Get statistics
        $stats = [
            'total_clients' => User::where('role', 'client')->count(),
            'active_clients' => User::where('role', 'client')->where('status', 'active')->count(),
            'total_projects' => Form::count(),
            'active_projects' => Task::where('status', 'in_progress')->count(),
        ];
        
        return view('admin.clients.index', compact('clients', 'stats'));
    }
    
    public function show($id)
    {
        $client = User::where('role', 'client')->with(['tasks', 'forms', 'feedbacks'])->findOrFail($id);
        
        // Get client statistics
        $stats = [
            'total_projects' => $client->forms()->count(),
            'completed_projects' => $client->tasks()->where('status', 'completed')->count(),
            'active_projects' => $client->tasks()->where('status', 'in_progress')->count(),
            'total_feedback' => $client->feedbacks()->count(),
        ];
        
        // Get recent activities
        $recentTasks = $client->tasks()->latest()->take(5)->get();
        $recentForms = $client->forms()->latest()->take(5)->get();
        
        // Get notes for this client
        $notes = Note::where('client_id', $id)->latest()->get();
        
        return view('admin.clients.show', compact('client', 'stats', 'recentTasks', 'recentForms', 'notes'));
    }
    
    public function create()
    {
        return view('admin.clients.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phoneNumber' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
        ]);
        
        $client = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'password' => bcrypt($request->password),
            'role' => 'client',
            'status' => $request->status,
        ]);
        
        return redirect()->route('admin.clients.index')
                        ->with('success', 'Client created successfully.');
    }
    
    public function edit($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }
    
    public function update(Request $request, $id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'phoneNumber' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,banned',
        ]);
        
        $client->update($request->only(['fullName', 'email', 'phoneNumber', 'status']));
        
        return redirect()->route('admin.clients.show', $client->id)
                        ->with('success', 'Client updated successfully.');
    }
    
    public function destroy($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $client->delete();
        
        return redirect()->route('admin.clients.index')
                        ->with('success', 'Client deleted successfully.');
    }
    
    public function addNote(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,important,reminder,issue',
        ]);
        
        Note::create([
            'client_id' => $id,
            'admin_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->input('content'),
            'type' => $request->type,
        ]);
        
        return redirect()->back()->with('success', 'Note added successfully.');
    }
    
    public function updateNote(Request $request, $clientId, $noteId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,important,reminder,issue',
        ]);
        
        $note = Note::where('client_id', $clientId)->findOrFail($noteId);
        $note->update($request->only(['title', 'content', 'type']));
        
        return redirect()->back()->with('success', 'Note updated successfully.');
    }
    
    public function deleteNote($clientId, $noteId)
    {
        $note = Note::where('client_id', $clientId)->findOrFail($noteId);
        $note->delete();
        
        return redirect()->back()->with('success', 'Note deleted successfully.');
    }
}