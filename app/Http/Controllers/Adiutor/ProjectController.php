<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\Document;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProjectController extends Controller
{
    /**
     * Display a listing of assigned projects
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get all assigned projects with details
        $query = DB::table('project_assignments')
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
            );
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('projects.title', 'like', "%{$search}%")
                  ->orWhere('projects.description', 'like', "%{$search}%")
                  ->orWhere('users.fullName', 'like', "%{$search}%");
            });
        }
        
        // Filter by assignment status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('project_assignments.status', $request->status);
        }
        
        // Filter by priority
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('projects.priority', $request->priority);
        }
        
        $projects = $query->orderBy('project_assignments.created_at', 'desc')->get();
        
        // Calculate earnings for each project
        foreach ($projects as $project) {
            $project->myEarnings = $this->calculateAdiutorEarningsForProject($project->id, $user->id);
        }
        
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
                'service_requests.payment_type',
                'service_requests.requested_features',
                'service_requests.requested_skills',
                'service_requests.template_features',
                'service_requests.template_skills',
                'service_requests.has_customizations'
            )
            ->first();
        
        if (!$project) {
            return redirect()->route('adiutor.projects.index')
                ->with('error', 'Project not found or you do not have access to it.');
        }

        // Create a service request object for the view (mimics the relationship)
        $serviceRequest = null;
        if ($project->service_request_id) {
            $serviceRequest = (object) [
                'service_type' => $project->service_type,
                'request_description' => $project->request_description,
                'payment_type' => $project->payment_type,
                'requested_features' => $project->requested_features ? json_decode($project->requested_features, true) : [],
                'requested_skills' => $project->requested_skills ? json_decode($project->requested_skills, true) : [],
                'template_features' => $project->template_features ? json_decode($project->template_features, true) : [],
                'template_skills' => $project->template_skills ? json_decode($project->template_skills, true) : [],
                'has_customizations' => $project->has_customizations ?? false,
                'effective_features' => $project->requested_features 
                    ? json_decode($project->requested_features, true) 
                    : ($project->template_features ? json_decode($project->template_features, true) : []),
                'effective_skills' => $project->requested_skills 
                    ? json_decode($project->requested_skills, true) 
                    : ($project->template_skills ? json_decode($project->template_skills, true) : []),
            ];
        }
        
        // Attach service request to project for view compatibility
        $project->serviceRequest = $serviceRequest;
        
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
            ->select(
                'taskID as id',
                'taskTitle as title',
                'taskDescription as description',
                'status',
                'priority',
                'deadline',
                'progress_percentage',
                'completedAt',
                'created_at'
            )
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

        // Get tasks with their deliverables for grouped display
        $tasksWithDeliverables = \App\Models\Task::where('project_id', $id)
            ->with(['documents' => function($query) {
                $query->where('is_archived', false)
                      ->with('uploader')
                      ->orderByDesc('is_deliverable')
                      ->orderByDesc('created_at');
            }, 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function($task) {
                return $task->documents->count() > 0;
            });

        // Get project-level documents (not associated with any task)
        $projectLevelDocuments = \App\Models\Document::where('project_id', $id)
            ->whereNull('taskID')
            ->where('is_archived', false)
            ->with('uploader')
            ->orderByDesc('is_deliverable')
            ->orderByDesc('created_at')
            ->get();

        // Count total and pending deliverables
        $totalDeliverables = $tasksWithDeliverables->sum(function($task) {
            return $task->documents->count();
        }) + $projectLevelDocuments->count();

        $pendingDeliverables = $tasksWithDeliverables->sum(function($task) {
            return $task->documents->where('is_deliverable', true)->where('is_approved', false)->count();
        }) + $projectLevelDocuments->where('is_deliverable', true)->where('is_approved', false)->count();
        
        // Calculate adiutor's earnings for this project
        $myEarnings = $this->calculateAdiutorEarningsForProject($id, $user->id);
        
        return view('adiutor.projects.show', compact(
            'user',
            'project',
            'milestones',
            'tasks',
            'teamMembers',
            'activities',
            'tasksWithDeliverables',
            'projectLevelDocuments',
            'totalDeliverables',
            'pendingDeliverables',
            'myEarnings'
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

    /**
     * Calculate adiutor's earnings for a specific project
     */
    private function calculateAdiutorEarningsForProject($projectId, $adiutorId)
    {
        // Calculate hourly earnings from time entries (use duration_minutes and convert to hours)
        $hourlyEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereHas('task', function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->whereNotNull('end_time')
            ->selectRaw('
                SUM(CASE WHEN is_approved = 1 AND is_paid = 0 THEN calculated_amount ELSE 0 END) as approved_amount,
                SUM(CASE WHEN is_paid = 1 THEN calculated_amount ELSE 0 END) as paid_amount,
                SUM(CASE WHEN is_approved = 0 THEN calculated_amount ELSE 0 END) as pending_amount,
                SUM(CASE WHEN is_approved = 1 AND is_paid = 0 THEN duration_minutes / 60.0 ELSE 0 END) as approved_hours,
                SUM(CASE WHEN is_paid = 1 THEN duration_minutes / 60.0 ELSE 0 END) as paid_hours,
                SUM(CASE WHEN is_approved = 0 THEN duration_minutes / 60.0 ELSE 0 END) as pending_hours,
                COUNT(*) as entry_count
            ')
            ->first();

        // Calculate fixed rate earnings from project_assignments (not documents)
        // Documents don't have is_paid or payout_amount columns
        $fixedRateAssignment = \DB::table('project_assignments')
            ->where('project_id', $projectId)
            ->where('adiutor_id', $adiutorId)
            ->where('payment_type', 'fixed_rate')
            ->select(
                'agreed_rate',
                'fixed_rate_approved',
                'fixed_rate_paid'
            )
            ->first();
        
        // Calculate fixed rate earnings based on approval and payment status
        $fixedRateEarnings = (object) [
            'approved_amount' => ($fixedRateAssignment && $fixedRateAssignment->fixed_rate_approved && !$fixedRateAssignment->fixed_rate_paid) 
                ? ($fixedRateAssignment->agreed_rate ?? 0) : 0,
            'paid_amount' => ($fixedRateAssignment && $fixedRateAssignment->fixed_rate_paid) 
                ? ($fixedRateAssignment->agreed_rate ?? 0) : 0,
            'pending_amount' => ($fixedRateAssignment && !$fixedRateAssignment->fixed_rate_approved) 
                ? ($fixedRateAssignment->agreed_rate ?? 0) : 0,
            'approved_count' => ($fixedRateAssignment && $fixedRateAssignment->fixed_rate_approved && !$fixedRateAssignment->fixed_rate_paid) ? 1 : 0,
            'paid_count' => ($fixedRateAssignment && $fixedRateAssignment->fixed_rate_paid) ? 1 : 0,
            'pending_count' => ($fixedRateAssignment && !$fixedRateAssignment->fixed_rate_approved && $fixedRateAssignment->agreed_rate) ? 1 : 0,
        ];

        // Get task allocations for this adiutor
        $taskAllocations = Task::where('project_id', $projectId)
            ->where('assignedTo', $adiutorId)
            ->sum('allocated_budget');

        // Calculate totals
        $totalApproved = ($hourlyEarnings->approved_amount ?? 0) + ($fixedRateEarnings->approved_amount ?? 0);
        $totalPaid = ($hourlyEarnings->paid_amount ?? 0) + ($fixedRateEarnings->paid_amount ?? 0);
        $totalPending = ($hourlyEarnings->pending_amount ?? 0) + ($fixedRateEarnings->pending_amount ?? 0);
        $totalEarned = $totalApproved + $totalPaid;
        $projectedTotal = $totalEarned + $totalPending;

        return [
            'hourly' => [
                'approved_amount' => $hourlyEarnings->approved_amount ?? 0,
                'paid_amount' => $hourlyEarnings->paid_amount ?? 0,
                'pending_amount' => $hourlyEarnings->pending_amount ?? 0,
                'approved_hours' => round($hourlyEarnings->approved_hours ?? 0, 2),
                'paid_hours' => round($hourlyEarnings->paid_hours ?? 0, 2),
                'pending_hours' => round($hourlyEarnings->pending_hours ?? 0, 2),
                'entry_count' => $hourlyEarnings->entry_count ?? 0,
            ],
            'fixed_rate' => [
                'approved_amount' => $fixedRateEarnings->approved_amount ?? 0,
                'paid_amount' => $fixedRateEarnings->paid_amount ?? 0,
                'pending_amount' => $fixedRateEarnings->pending_amount ?? 0,
                'approved_count' => $fixedRateEarnings->approved_count ?? 0,
                'paid_count' => $fixedRateEarnings->paid_count ?? 0,
                'pending_count' => $fixedRateEarnings->pending_count ?? 0,
            ],
            'task_allocations' => $taskAllocations,
            'total_approved' => $totalApproved,
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'total_earned' => $totalEarned,
            'projected_total' => $projectedTotal,
        ];
    }
}
