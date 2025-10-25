<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ProjectCompletedNotification;

class ProjectManagementController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $query = Project::with(['serviceRequest', 'client', 'adiutors']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('client', function($clientQuery) use ($searchTerm) {
                      $clientQuery->where('fullName', 'LIKE', "%{$searchTerm}%")
                                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $projects = $query->orderBy($sortField, $sortDirection)->paginate(15);

        // Get statistics
        $stats = [
            'total' => Project::count(),
            'active' => Project::where('status', 'active')->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'review' => Project::where('status', 'review')->count(),
            'cancelled' => Project::where('status', 'cancelled')->count(),
        ];

        // Get clients for filter dropdown
        $clients = User::where('role', 'client')->select('id', 'fullName')->get();

        return view('admin.projects.index', compact('projects', 'stats', 'clients'));
    }

    /**
     * Show the specified project
     */
    public function show($id)
    {
        $project = Project::with(['serviceRequest', 'client', 'adiutors', 'tasks.assignedUser', 'tasks.creator'])
                         ->findOrFail($id);

        // Calculate budget overview
        $totalAllocated = $project->tasks->sum('allocated_budget') ?? 0;
        $totalSpent = $project->tasks->sum('actual_cost') ?? 0;
        $remainingBudget = ($project->budget ?? 0) - $totalAllocated;

        $budgetOverview = [
            'project_budget' => $project->budget,
            'total_allocated' => $totalAllocated,
            'total_spent' => $totalSpent,
            'remaining_budget' => $remainingBudget,
            'is_over_budget' => $remainingBudget < 0,
            'budget_utilization_percentage' => $project->budget > 0 ? round(($totalAllocated / $project->budget) * 100, 2) : 0
        ];

        // Get available adiutors for assignment
        $availableAdiutors = User::where('role', 'adiutor')->select('id', 'fullName')->get();

        return view('admin.projects.show', compact('project', 'budgetOverview', 'availableAdiutors'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        // Projects are typically created automatically from paid service requests
        // This method can be used for manual project creation if needed
        $clients = User::where('role', 'client')->select('id', 'fullName')->get();
        $serviceRequests = ServiceRequest::where('status', 'paid')
                                        ->whereDoesntHave('project')
                                        ->with('client')
                                        ->get();
        
        return view('admin.projects.create', compact('clients', 'serviceRequests'));
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id|unique:projects,service_request_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'budget' => 'required|numeric|min:0',
            'budget_type' => 'required|in:fixed,hourly',
            'deadline' => 'nullable|date|after:today',
            'priority' => 'required|in:low,medium,high,urgent',
            'requirements' => 'nullable|json',
            'skills_required' => 'nullable|json',
        ]);

        // Get service request to get client_id
        $serviceRequest = ServiceRequest::findOrFail($request->service_request_id);

        $project = Project::create([
            'service_request_id' => $request->service_request_id,
            'client_id' => $serviceRequest->client_id,
            'title' => $request->title,
            'description' => $request->description,
            'budget' => $request->budget,
            'budget_type' => $request->budget_type,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'requirements' => $request->requirements ? json_decode($request->requirements) : null,
            'skills_required' => $request->skills_required ? json_decode($request->skills_required) : null,
            'status' => 'active',
            'started_at' => now(),
        ]);

        return redirect()->route('admin.projects.show', $project->id)
                        ->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit($id)
    {
        $project = Project::with(['serviceRequest', 'client'])->findOrFail($id);
        $clients = User::where('role', 'client')->select('id', 'fullName')->get();
        
        return view('admin.projects.edit', compact('project', 'clients'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'budget' => 'required|numeric|min:0',
            'budget_type' => 'required|in:fixed,hourly',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'requirements' => 'nullable|json',
            'skills_required' => 'nullable|json',
        ]);

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'budget' => $request->budget,
            'budget_type' => $request->budget_type,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'requirements' => $request->requirements ? json_decode($request->requirements) : null,
            'skills_required' => $request->skills_required ? json_decode($request->skills_required) : null,
        ]);

        return redirect()->route('admin.projects.show', $id)
                        ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage
     */
    public function destroy($id)
    {
        $project = Project::with('tasks')->findOrFail($id);
        
        // Check if project has tasks
        if ($project->tasks->count() > 0) {
            return redirect()->back()
                           ->with('error', 'Cannot delete project with assigned tasks. Please reassign or complete all tasks first.');
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
                        ->with('success', 'Project deleted successfully.');
    }

    /**
     * Mark project as completed
     */
    public function complete(Request $request, $id)
    {
        $request->validate([
            'completion_notes' => 'nullable|string|max:1000',
        ]);

        $project = Project::with('client')->findOrFail($id);
        
        // Update project to completed status
        $project->update([
            'status' => 'completed',
            'completion_date' => now(),
            'notes' => $request->completion_notes ? $project->notes . "\n\nCompletion Notes: " . $request->completion_notes : $project->notes,
        ]);

        // Create notification for client
        $client = User::find($project->client_id);
        if ($client) {
            $client->notify(new ProjectCompletedNotification($project->id, $project->title));
        }

        return redirect()->route('admin.projects.show', $id)
                        ->with('success', 'Project marked as completed successfully!');
    }

    /**
     * Update project status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,in_progress,review,completed,cancelled',
        ]);

        $project = Project::findOrFail($id);
        
        $updateData = ['status' => $request->status];
        
        // Update completed_at if status changed to completed
        if ($request->status === 'completed' && $project->status !== 'completed') {
            $updateData['completed_at'] = now();
        } elseif ($request->status !== 'completed') {
            $updateData['completed_at'] = null;
        }
        
        $project->update($updateData);

        return redirect()->back()->with('success', 'Project status updated successfully.');
    }

    /**
     * Assign adiutor to project
     */
    public function assignAdiutor(Request $request, $id)
    {
        $request->validate([
            'adiutor_id' => 'required|exists:users,id',
            'agreed_rate' => 'nullable|numeric|min:0',
            'expected_completion' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $project = Project::findOrFail($id);
        
        // Check if adiutor is already assigned
        $existingAssignment = DB::table('project_assignments')
            ->where('project_id', $id)
            ->where('adiutor_id', $request->adiutor_id)
            ->first();
            
        if ($existingAssignment) {
            return redirect()->back()->with('error', 'Adiutor is already assigned to this project.');
        }

        DB::table('project_assignments')->insert([
            'project_id' => $id,
            'adiutor_id' => $request->adiutor_id,
            'agreed_rate' => $request->agreed_rate,
            'start_date' => now(),
            'expected_completion' => $request->expected_completion,
            'status' => 'assigned',
            'notes' => $request->notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Adiutor assigned to project successfully.');
    }

    /**
     * Remove adiutor from project
     */
    public function removeAdiutor($projectId, $adiutorId)
    {
        DB::table('project_assignments')
            ->where('project_id', $projectId)
            ->where('adiutor_id', $adiutorId)
            ->update([
                'status' => 'removed',
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Adiutor removed from project successfully.');
    }

    /**
     * Get team members for a project (for AJAX)
     */
    public function getTeamMembers($projectId)
    {
        $teamMembers = DB::table('users')
            ->join('project_assignments', 'users.id', '=', 'project_assignments.adiutor_id')
            ->where('project_assignments.project_id', $projectId)
            ->whereIn('project_assignments.status', ['assigned', 'accepted', 'in_progress'])
            ->select('users.id', 'users.fullName')
            ->orderBy('users.fullName')
            ->get();
        
        return response()->json($teamMembers);
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
                $projectsWithTasks = Task::whereIn('project_id', $projectIds)
                    ->pluck('project_id')
                    ->unique()
                    ->toArray();

                if (!empty($projectsWithTasks)) {
                    return redirect()->back()->with('error', 'Cannot delete projects with assigned tasks.');
                }

                Project::whereIn('id', $projectIds)->delete();
                return redirect()->back()->with('success', count($projectIds) . ' projects deleted successfully.');

            case 'update_status':
                $request->validate(['bulk_status' => 'required|in:active,in_progress,review,completed,cancelled']);
                
                $updateData = ['status' => $request->bulk_status];
                
                // If marking as completed, set completed_at
                if ($request->bulk_status === 'completed') {
                    $updateData['completed_at'] = now();
                }
                
                Project::whereIn('id', $projectIds)->update($updateData);
                
                return redirect()->back()->with('success', count($projectIds) . ' projects status updated successfully.');

            case 'update_priority':
                $request->validate(['bulk_priority' => 'required|in:low,medium,high,urgent']);
                
                Project::whereIn('id', $projectIds)->update(['priority' => $request->bulk_priority]);
                
                return redirect()->back()->with('success', count($projectIds) . ' projects priority updated successfully.');

            default:
                return redirect()->back()->with('error', 'Invalid action.');
        }
    }
}