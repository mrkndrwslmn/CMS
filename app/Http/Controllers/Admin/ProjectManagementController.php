<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectManagementController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $query = DB::table('projects')
            ->leftJoin('service_requests', 'projects.service_request_id', '=', 'service_requests.id')
            ->leftJoin('users as clients', 'projects.client_id', '=', 'clients.id')
            ->select(
                'projects.*',
                'service_requests.project_name as original_request_name',
                'service_requests.status as request_status',
                'clients.fullName as client_name',
                'clients.email as client_email'
            );

        // Apply filters
        if ($request->filled('status')) {
            $query->where('projects.status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('projects.priority', $request->priority);
        }

        if ($request->filled('client_id')) {
            $query->where('projects.client_id', $request->client_id);
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('projects.title', 'LIKE', $searchTerm)
                  ->orWhere('projects.description', 'LIKE', $searchTerm)
                  ->orWhere('clients.fullName', 'LIKE', $searchTerm)
                  ->orWhere('clients.email', 'LIKE', $searchTerm);
            });
        }

        if ($request->filled('date_from')) {
            $query->where('projects.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('projects.created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $projects = $query->orderBy($sortField, $sortDirection)->paginate(15);

        // Get statistics
        $stats = [
            'total' => DB::table('projects')->count(),
            'open' => DB::table('projects')->where('status', 'open')->count(),
            'in_progress' => DB::table('projects')->where('status', 'in_progress')->count(),
            'completed' => DB::table('projects')->where('status', 'completed')->count(),
            'on_hold' => DB::table('projects')->where('status', 'on_hold')->count(),
            'cancelled' => DB::table('projects')->where('status', 'cancelled')->count(),
        ];

        // Get clients for filter dropdown
        $clients = DB::table('users')->where('role', 'client')->select('id', 'fullName')->get();

        return view('admin.projects.index', compact('projects', 'stats', 'clients'));
    }

    /**
     * Show the specified project
     */
    public function show($id)
    {
        $project = DB::table('projects')
            ->leftJoin('service_requests', 'projects.service_request_id', '=', 'service_requests.id')
            ->leftJoin('users as clients', 'projects.client_id', '=', 'clients.id')
            ->where('projects.id', $id)
            ->select(
                'projects.*',
                'service_requests.project_name as original_request_name',
                'service_requests.status as request_status',
                'service_requests.payment_method',
                'service_requests.payment_reference',
                'clients.fullName as client_name',
                'clients.email as client_email',
                'clients.phone as client_phone'
            )
            ->first();

        if (!$project) {
            return redirect()->route('admin.projects.index')->with('error', 'Project not found.');
        }

        // Get project tasks
        $tasks = DB::table('tasks')
            ->leftJoin('users as assignees', 'tasks.assignedTo', '=', 'assignees.id')
            ->leftJoin('users as creators', 'tasks.createdBy', '=', 'creators.id')
            ->where('tasks.project_id', $id)
            ->select(
                'tasks.*',
                'assignees.fullName as assignee_name',
                'creators.fullName as creator_name'
            )
            ->orderBy('tasks.created_at', 'desc')
            ->get();

        // Calculate budget overview
        $totalAllocated = $tasks->sum('allocated_budget') ?? 0;
        $totalSpent = $tasks->sum('actual_cost') ?? 0;
        $remainingBudget = $project->approved_budget - $totalAllocated;

        $budgetOverview = [
            'approved_budget' => $project->approved_budget,
            'total_allocated' => $totalAllocated,
            'total_spent' => $totalSpent,
            'remaining_budget' => $remainingBudget,
            'is_over_budget' => $remainingBudget < 0,
            'budget_utilization_percentage' => $project->approved_budget > 0 ? round(($totalAllocated / $project->approved_budget) * 100, 2) : 0
        ];

        // Get available adiutors for task assignment
        $adiutors = DB::table('users')->where('role', 'adiutor')->select('id', 'fullName')->get();

        return view('admin.projects.show', compact('project', 'tasks', 'budgetOverview', 'adiutors'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        // Projects are created automatically from service requests
        // This method can be used for manual project creation if needed
        $clients = DB::table('users')->where('role', 'client')->select('id', 'fullName')->get();
        return view('admin.projects.create', compact('clients'));
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'budget' => 'required|numeric|min:0',
            'approved_budget' => 'required|numeric|min:0',
            'deadline' => 'nullable|date|after:today',
            'priority' => 'required|in:low,medium,high',
            'project_notes' => 'nullable|string|max:1000'
        ]);

        $projectId = DB::table('projects')->insertGetId([
            'client_id' => $request->client_id,
            'title' => $request->title,
            'description' => $request->description,
            'budget' => $request->budget,
            'approved_budget' => $request->approved_budget,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'status' => 'open',
            'project_notes' => $request->project_notes,
            'project_started_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.projects.show', $projectId)->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit($id)
    {
        $project = DB::table('projects')->where('id', $id)->first();
        
        if (!$project) {
            return redirect()->route('admin.projects.index')->with('error', 'Project not found.');
        }

        $clients = DB::table('users')->where('role', 'client')->select('id', 'fullName')->get();
        
        return view('admin.projects.edit', compact('project', 'clients'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'budget' => 'required|numeric|min:0',
            'approved_budget' => 'required|numeric|min:0',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'project_notes' => 'nullable|string|max:1000'
        ]);

        $updated = DB::table('projects')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'description' => $request->description,
                'budget' => $request->budget,
                'approved_budget' => $request->approved_budget,
                'deadline' => $request->deadline,
                'priority' => $request->priority,
                'project_notes' => $request->project_notes,
                'updated_at' => now()
            ]);

        if (!$updated) {
            return redirect()->back()->with('error', 'Project not found or could not be updated.');
        }

        return redirect()->route('admin.projects.show', $id)->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage
     */
    public function destroy($id)
    {
        $project = DB::table('projects')->where('id', $id)->first();
        
        if (!$project) {
            return redirect()->route('admin.projects.index')->with('error', 'Project not found.');
        }

        // Check if project has tasks
        $taskCount = DB::table('tasks')->where('project_id', $id)->count();
        
        if ($taskCount > 0) {
            return redirect()->back()->with('error', 'Cannot delete project with assigned tasks. Please reassign or complete all tasks first.');
        }

        DB::table('projects')->where('id', $id)->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    /**
     * Update project status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,completed,on_hold,cancelled',
            'status_notes' => 'nullable|string|max:500'
        ]);

        $updated = DB::table('projects')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'status_notes' => $request->status_notes,
                'updated_at' => now()
            ]);

        if (!$updated) {
            return redirect()->back()->with('error', 'Project not found.');
        }

        return redirect()->back()->with('success', 'Project status updated successfully.');
    }

    /**
     * Add a note to the project
     */
    public function addNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        $currentNotes = DB::table('projects')->where('id', $id)->value('project_notes') ?? '';
        $newNote = "[" . now()->format('Y-m-d H:i:s') . " - " . Auth::user()->fullName . "] " . $request->note;
        $updatedNotes = $currentNotes ? $currentNotes . "\n\n" . $newNote : $newNote;

        $updated = DB::table('projects')
            ->where('id', $id)
            ->update([
                'project_notes' => $updatedNotes,
                'updated_at' => now()
            ]);

        if (!$updated) {
            return redirect()->back()->with('error', 'Project not found.');
        }

        return redirect()->back()->with('success', 'Note added successfully.');
    }

    /**
     * Handle bulk actions on projects
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,update_status,update_priority',
            'project_ids' => 'required|array',
            'project_ids.*' => 'exists:projects,id'
        ]);

        $projectIds = $request->project_ids;
        $action = $request->action;

        switch ($action) {
            case 'delete':
                // Check if any projects have tasks
                $projectsWithTasks = DB::table('tasks')
                    ->whereIn('project_id', $projectIds)
                    ->pluck('project_id')
                    ->unique()
                    ->toArray();

                if (!empty($projectsWithTasks)) {
                    return redirect()->back()->with('error', 'Cannot delete projects with assigned tasks.');
                }

                DB::table('projects')->whereIn('id', $projectIds)->delete();
                return redirect()->back()->with('success', count($projectIds) . ' projects deleted successfully.');

            case 'update_status':
                $request->validate(['bulk_status' => 'required|in:open,in_progress,completed,on_hold,cancelled']);
                
                DB::table('projects')
                    ->whereIn('id', $projectIds)
                    ->update([
                        'status' => $request->bulk_status,
                        'updated_at' => now()
                    ]);
                
                return redirect()->back()->with('success', count($projectIds) . ' projects status updated successfully.');

            case 'update_priority':
                $request->validate(['bulk_priority' => 'required|in:low,medium,high']);
                
                DB::table('projects')
                    ->whereIn('id', $projectIds)
                    ->update([
                        'priority' => $request->bulk_priority,
                        'updated_at' => now()
                    ]);
                
                return redirect()->back()->with('success', count($projectIds) . ' projects priority updated successfully.');

            default:
                return redirect()->back()->with('error', 'Invalid action.');
        }
    }
}