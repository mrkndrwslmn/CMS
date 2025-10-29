<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProjectController extends Controller
{
    /**
     * Display a listing of assigned projects
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all assigned projects with details
        $projects = DB::table('project_assignments')
            ->join('projects', 'project_assignments.project_id', '=', 'projects.id')
            ->join('users', 'projects.client_id', '=', 'users.id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->select(
                'projects.*',
                'project_assignments.id as assignment_id',
                'project_assignments.status as assignment_status',
                'project_assignments.agreed_rate',
                'project_assignments.start_date',
                'project_assignments.expected_completion',
                'project_assignments.progress_percentage',
                'project_assignments.notes',
                'users.fullName as client_name',
                'users.email as client_email',
                'users.profilePic as client_photo'
            )
            ->orderBy('project_assignments.created_at', 'desc')
            ->get();
        
        return view('adiutor.projects.index', compact('user', 'projects'));
    }

    /**
     * Display the specified project
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Get project details with relationship data
        $project = DB::table('projects')
            ->join('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
            ->join('users as clients', 'projects.client_id', '=', 'clients.id')
            ->join('service_requests', 'projects.service_request_id', '=', 'service_requests.id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->where('projects.id', $id)
            ->select(
                'projects.*',
                'project_assignments.id as assignment_id',
                'project_assignments.status as assignment_status',
                'project_assignments.agreed_rate',
                'project_assignments.start_date',
                'project_assignments.expected_completion',
                'project_assignments.progress_percentage',
                'project_assignments.notes as assignment_notes',
                'clients.fullName as client_name',
                'clients.email as client_email',
                'clients.phoneNumber as client_phone',
                'clients.profilePic as client_photo',
                'service_requests.service_type',
                'service_requests.request_description',
                'service_requests.payment_type'
            )
            ->first();
        
        if (!$project) {
            return redirect()->route('adiutor.projects.index')
                ->with('error', 'Project not found or you do not have access to it.');
        }
        
        // Get project milestones if payment type is milestone
        $milestones = [];
        if ($project->payment_type === 'milestone_payment') {
            $milestones = DB::table('project_milestones')
                ->where('project_id', $id)
                ->orderBy('phase_order')
                ->get();
        }
        
        // Get tasks associated with this project
        $tasks = DB::table('tasks')
            ->where('project_id', $id)
            ->where('assignedTo', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get team members on this project - safely handle no results
        $teamMembers = DB::table('project_assignments')
            ->join('users', 'project_assignments.adiutor_id', '=', 'users.id')
            ->where('project_assignments.project_id', $id)
            ->select(
                'users.id',
                'users.fullName',
                'users.email',
                'users.profilePic',
                'project_assignments.status as assignment_status'
            )
            ->get();
        
        // Ensure we have at least empty collection if no team members
        $teamMembers = $teamMembers ?: collect();
        
        // Get recent activity/notes (placeholder - notes table doesn't exist yet)
        $activities = collect(); // Empty collection until notes system is implemented
        
        return view('adiutor.projects.show', compact(
            'user',
            'project',
            'milestones',
            'tasks',
            'teamMembers',
            'activities'
        ));
    }

    /**
     * Update project progress
     */
    public function updateProgress(Request $request, $assignmentId)
    {
        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string'
        ]);
        
        $updated = DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->where('adiutor_id', Auth::id())
            ->update([
                'progress_percentage' => $request->progress_percentage,
                'notes' => $request->notes,
                'updated_at' => now()
            ]);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Progress updated successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to update progress'
        ], 400);
    }

    /**
     * Accept project assignment
     */
    public function accept($assignmentId)
    {
        $user = Auth::user();
        
        $assignment = DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->where('adiutor_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or already processed'
            ], 404);
        }
        
        DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->update([
                'status' => 'active',
                'start_date' => now(),
                'updated_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Project accepted successfully'
        ]);
    }

    /**
     * Decline project assignment
     */
    public function decline(Request $request, $assignmentId)
    {
        $user = Auth::user();
        
        $assignment = DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->where('adiutor_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or already processed'
            ], 404);
        }
        
        $reason = $request->input('reason', 'No reason provided');
        
        DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->update([
                'status' => 'declined',
                'notes' => 'Declined by adiutor. Reason: ' . $reason,
                'updated_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Project declined successfully'
        ]);
    }

    /**
     * Create a new task for the project (requires admin approval)
     */
    public function createTask(Request $request, $projectId)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'nullable|date',
            'allocated_budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        
        // Verify adiutor is assigned to this project
        $assignment = DB::table('project_assignments')
            ->where('project_id', $projectId)
            ->where('adiutor_id', $user->id)
            ->where('status', 'active')
            ->first();
            
        if (!$assignment) {
            return redirect()->back()
                ->withErrors(['error' => 'You are not assigned to this project or the project is not active.']);
        }
        
        // Get project and client details
        $project = DB::table('projects')
            ->where('id', $projectId)
            ->first();
            
        if (!$project) {
            return redirect()->back()
                ->withErrors(['error' => 'Project not found.']);
        }
        
        // Create task with pending_approval status
        $taskId = DB::table('tasks')->insertGetId([
            'project_id' => $projectId,
            'client_id' => $project->client_id,
            'assignedTo' => $user->id,
            'createdBy' => $user->id,
            'taskTitle' => $request->task_title,
            'taskDescription' => $request->task_description,
            'status' => 'pending_approval', // Special status requiring admin approval
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'allocated_budget' => $request->allocated_budget,
            'notes' => $request->notes,
            'dateAssigned' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Notify all admins about the new task request
        $admins = DB::table('users')->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            DB::table('notifications')->insert([
                'user_id' => $admin->id,
                'type' => 'task_approval_request',
                'title' => 'New Task Approval Required',
                'message' => $user->fullName . ' has created a new task "' . $request->task_title . '" for project "' . $project->title . '" that requires your approval.',
                'data' => json_encode([
                    'task_id' => $taskId,
                    'project_id' => $projectId,
                    'adiutor_id' => $user->id,
                    'adiutor_name' => $user->fullName,
                    'task_title' => $request->task_title,
                    'project_title' => $project->title,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        return redirect()->back()
            ->with('success', 'Task created successfully! It will be activated once an admin approves it.');
    }
}
