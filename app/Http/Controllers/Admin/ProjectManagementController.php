<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Task;
use App\Mail\ProjectAssigned;
use App\Mail\ProjectCancelledMail;
use App\Mail\AdiutorRemovedFromProjectMail;
use App\Notifications\ProjectCompletedNotification;
use App\Notifications\ProjectCreatedNotification;
use App\Notifications\ProjectStatusChangedNotification;
use App\Notifications\AdiutorRemovedFromProjectNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

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

        // Get available adiutors for assignment with detailed information
        // Get project's required service type for skill matching
        $projectServiceType = $project->serviceRequest ? $project->serviceRequest->service_type : null;

        $availableAdiutors = User::where('role', 'adiutor')
            ->with([
                'calendarIntegration',
                'adiutorProfile.skills',  // Load skills through adiutorProfile
                'assignedProjects' => function($query) {
                    $query->whereIn('project_assignments.status', ['assigned', 'active', 'in_progress']);
                }
            ])
            ->get()
            ->map(function ($adiutor) use ($projectServiceType) {
                $skills = $adiutor->adiutorProfile ? $adiutor->adiutorProfile->skills : collect();
                $activeProjectsCount = $adiutor->assignedProjects->count();
                
                // Calculate ranking score
                // New weighting: Skills (70 points) prioritized, Workload (30 points)
                $skillScore = 0; // up to 70
                $workloadScore = 0; // up to 30

                // Workload Score (0-30 points)
                // Lower workload = higher score
                // 0 projects = 30 points, 1-2 = 24, 3-4 = 18, 5-6 = 12, 7+ = 6
                if ($activeProjectsCount == 0) {
                    $workloadScore = 30;
                } elseif ($activeProjectsCount <= 2) {
                    $workloadScore = 24;
                } elseif ($activeProjectsCount <= 4) {
                    $workloadScore = 18;
                } elseif ($activeProjectsCount <= 6) {
                    $workloadScore = 12;
                } else {
                    $workloadScore = 6;
                }

                // Skill Match Score (0-70 points)
                // Use category-based matching for better service type to skill matching
                $contrib = 0.0;
                if ($projectServiceType && $skills->isNotEmpty()) {
                    // Define skill categories for better matching
                    $skillCategories = [
                        'programming' => ['php', 'javascript', 'python', 'java', 'c#', 'ruby', 'typescript', 'node', 'laravel', 'django', 'react', 'vue', 'angular', 'flutter', 'swift', 'kotlin', 'api'],
                        'design' => ['ui', 'ux', 'figma', 'photoshop', 'illustrator', 'graphic', 'logo', 'responsive', 'prototyp', 'adobe', 'web design'],
                        'marketing' => ['seo', 'google ads', 'social media', 'content', 'email', 'copywriting'],
                        'database' => ['mysql', 'mongodb', 'postgresql', 'sql', 'oracle', 'sqlite', 'database'],
                        'mobile' => ['ios', 'android', 'mobile', 'swift', 'kotlin'],
                        'devops' => ['docker', 'kubernetes', 'aws', 'azure', 'cloud', 'linux'],
                        'web' => ['html', 'css', 'javascript', 'react', 'vue', 'angular', 'node', 'laravel', 'php', 'web'],
                    ];
                    
                    $normalizedServiceType = strtolower(trim($projectServiceType));
                    
                    // Get relevant keywords for the service type
                    $relevantKeywords = [];
                    foreach ($skillCategories as $category => $keywords) {
                        if (str_contains($normalizedServiceType, $category) || $category === $normalizedServiceType) {
                            $relevantKeywords = array_merge($relevantKeywords, $keywords);
                        }
                    }
                    
                    // If no category match, use the service type itself
                    if (empty($relevantKeywords)) {
                        $relevantKeywords[] = str_replace(['-', '_', ' '], '', $normalizedServiceType);
                    }
                    
                    // Check each skill against relevant keywords
                    foreach ($skills as $skill) {
                        $skillName = strtolower(str_replace(['-', '_', ' ', '/'], '', $skill->name));
                        $isMatch = false;
                        
                        foreach ($relevantKeywords as $keyword) {
                            $normalizedKeyword = str_replace(['-', '_', ' ', '/'], '', $keyword);
                            if (str_contains($skillName, $normalizedKeyword) || str_contains($normalizedKeyword, $skillName)) {
                                $isMatch = true;
                                break;
                            }
                        }
                        
                        if ($isMatch) {
                            // get proficiency (1-5) if available, else default to 3
                            $prof = 3;
                            if (isset($skill->pivot) && isset($skill->pivot->proficiency_level)) {
                                $p = $skill->pivot->proficiency_level;
                                if (is_numeric($p)) {
                                    $prof = max(1, min(5, (int)$p));
                                } else {
                                    // map common strings
                                    $pl = strtolower(trim($p));
                                    if (in_array($pl, ['beginner','junior'])) $prof = 2;
                                    elseif (in_array($pl, ['intermediate','mid'])) $prof = 3;
                                    elseif (in_array($pl, ['advanced','senior','expert'])) $prof = 5;
                                }
                            }

                            $years = 0;
                            if (isset($skill->pivot) && isset($skill->pivot->years_experience) && is_numeric($skill->pivot->years_experience)) {
                                $years = max(0, min(20, (float)$skill->pivot->years_experience));
                            }

                            // Contribution formula: (proficiency/5) * (1 + years/10)
                            // Max per-match contribution ~= 2 (when prof=5 and years>=10)
                            $contrib += ($prof / 5) * (1 + ($years / 10));
                        }
                    }

                    // Scale contribution to skillScore cap (70). Each unit of contrib ≈ 20 points, but we cap at 70.
                    $skillScore = min($contrib * 20, 70);
                } elseif ($skills->isNotEmpty()) {
                    // No explicit service type match, but adiutor has skills: give a small baseline
                    $skillScore = 20;
                } else {
                    $skillScore = 0;
                }

                // Final aggregated score (0-100)
                $score = round($skillScore + $workloadScore, 2);
                
                return [
                    'id' => $adiutor->id,
                    'fullName' => $adiutor->fullName,
                    'email' => $adiutor->email,
                    'calendar_connected' => $adiutor->calendarIntegration && $adiutor->calendarIntegration->is_connected,
                    'skills' => $skills,
                    'rating' => $adiutor->adiutorProfile->rating ?? 0,
                    'active_projects_count' => $activeProjectsCount,
                    'rank_score' => $score,
                    'skill_score' => $skillScore,
                    'workload_score' => $workloadScore,
                    'has_matching_skill' => ($contrib > 0),
                ];
            })
            ->sortByDesc('rank_score') // Sort by rank score (highest first)
            ->values(); // Reset array keys

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

        // 🔔 Notify all admins about new project creation
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            $admin->notify(new ProjectCreatedNotification($project, Auth::user()->fullName));
        }

        // 🔔 Notify client about project creation
        $client = User::find($project->client_id);
        if ($client) {
            $client->notify(new ProjectCreatedNotification($project, 'Admin'));
        }

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

        $project = Project::with(['client', 'adiutors'])->findOrFail($id);
        $oldStatus = $project->status;
        
        $updateData = ['status' => $request->status];
        
        // Update completed_at if status changed to completed
        if ($request->status === 'completed' && $project->status !== 'completed') {
            $updateData['completed_at'] = now();
        } elseif ($request->status !== 'completed') {
            $updateData['completed_at'] = null;
        }
        
        $project->update($updateData);

        // 🔔 Notify client about status change
        if ($project->client) {
            $project->client->notify(new ProjectStatusChangedNotification($project, $oldStatus, Auth::user()->fullName));
        }

        // 🔔 Notify assigned adiutors about status change
        foreach ($project->adiutors as $adiutor) {
            $adiutor->notify(new ProjectStatusChangedNotification($project, $oldStatus, Auth::user()->fullName));
        }

        // 📧 Send email for important status changes
        try {
            if ($request->status === 'cancelled') {
                // Notify client
                if ($project->client) {
                    Mail::to($project->client->email)->send(new ProjectCancelledMail($project, 'Project cancelled by admin'));
                }
                
                // Notify all assigned adiutors
                foreach ($project->adiutors as $adiutor) {
                    Mail::to($adiutor->email)->send(new ProjectCancelledMail($project, 'Project cancelled by admin'));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send project status change email', [
                'project_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('success', 'Project status updated successfully.');
    }

    /**
     * Assign adiutor to project
     */
    public function assignAdiutor(Request $request, $id)
    {
        $request->validate([
            'adiutor_id' => 'required|exists:users,id',
            'hourly_rate' => 'nullable|numeric|min:0',
            'requires_time_tracking' => 'nullable|boolean',
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
            'hourly_rate' => $request->hourly_rate,
            'requires_time_tracking' => $request->has('requires_time_tracking'),
            'agreed_rate' => $request->agreed_rate,
            'start_date' => now(),
            'expected_completion' => $request->expected_completion,
            'status' => 'assigned',
            'notes' => $request->notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send project assignment email
        try {
            $adiutor = User::find($request->adiutor_id);
            $assignedBy = Auth::user();
            
            if ($adiutor && $adiutor->email) {
                Mail::to($adiutor->email)->send(new ProjectAssigned($project, $adiutor, $assignedBy));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send project assignment email', [
                'project_id' => $id,
                'adiutor_id' => $request->adiutor_id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('success', 'Adiutor assigned to project successfully.');
    }

    /**
     * Remove adiutor from project
     */
    public function removeAdiutor($projectId, $adiutorId)
    {
        $project = Project::findOrFail($projectId);
        $adiutor = User::findOrFail($adiutorId);

        DB::table('project_assignments')
            ->where('project_id', $projectId)
            ->where('adiutor_id', $adiutorId)
            ->update([
                'status' => 'removed',
                'updated_at' => now(),
            ]);

        // 🔔 Notify the removed adiutor
        $adiutor->notify(new AdiutorRemovedFromProjectNotification(
            $project->title,
            $project->id,
            $project->client->fullName ?? 'Unknown Client',
            Auth::user()->fullName
        ));

        // 📧 Send email notification to removed adiutor
        try {
            Mail::to($adiutor->email)->send(new AdiutorRemovedFromProjectMail(
                $project->title,
                $project->client->fullName ?? 'Unknown Client',
                'Removed by project administrator'
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send adiutor removal email', [
                'project_id' => $projectId,
                'adiutor_id' => $adiutorId,
                'error' => $e->getMessage()
            ]);
        }

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
     * Get project phases/milestones (for AJAX)
     */
    public function getProjectPhases($projectId)
    {
        $project = Project::with('serviceRequest')->findOrFail($projectId);
        
        // Check if project has milestone payment type
        $hasMilestonePayment = $project->serviceRequest && 
                              $project->serviceRequest->payment_type === 'milestone_payment';
        
        if (!$hasMilestonePayment) {
            return response()->json([
                'hasMilestones' => false,
                'phases' => []
            ]);
        }
        
        // Get milestones ordered by phase_order
        $phases = DB::table('project_milestones')
            ->where('project_id', $projectId)
            ->orderBy('phase_order', 'asc')
            ->select('id', 'phase_name', 'phase_order', 'status', 'is_paid')
            ->get();
        
        // Get current active phase (first unpaid or in_progress)
        $currentPhaseId = DB::table('project_milestones')
            ->where('project_id', $projectId)
            ->where('is_paid', false)
            ->orderBy('phase_order', 'asc')
            ->value('id');
        
        return response()->json([
            'hasMilestones' => true,
            'phases' => $phases,
            'currentPhaseId' => $currentPhaseId
        ]);
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

    /**
     * Show task scheduling page for a project
     */
    public function schedule($id)
    {
        $project = Project::with([
            'client',
            'tasks' => function($query) {
                $query->with('assignedUser');
            },
            'assignments' => function($query) {
                $query->whereIn('status', ['active', 'in_progress'])
                      ->with(['adiutor.adiutorProfile', 'adiutor.calendarIntegration']);
            }
        ])->findOrFail($id);

        // Prevent access to completed/archived projects
        if (!in_array($project->status, ['active', 'in_progress'])) {
            return redirect()->route('admin.calendar.index')
                ->with('error', 'Cannot schedule tasks for completed or archived projects.');
        }

        // Get assigned adiutors from both project assignments AND task assignments
        $adiutorsFromAssignments = $project->assignments->map(function($assignment) {
            return [
                'id' => $assignment->adiutor->id,
                'name' => $assignment->adiutor->fullName,
                'avatar' => $assignment->adiutor->avatar ?? '/images/default-avatar.png',
                'calendar_connected' => $assignment->adiutor->calendarIntegration && $assignment->adiutor->calendarIntegration->is_connected,
            ];
        });

        // Get adiutors assigned to tasks
        $adiutorsFromTasks = collect($project->tasks)
            ->filter(function($task) {
                return $task->assignedUser && $task->assignedUser->role === 'adiutor';
            })
            ->map(function($task) {
                return [
                    'id' => $task->assignedUser->id,
                    'name' => $task->assignedUser->fullName,
                    'avatar' => $task->assignedUser->avatar ?? '/images/default-avatar.png',
                    'calendar_connected' => $task->assignedUser->calendarIntegration && $task->assignedUser->calendarIntegration->is_connected,
                ];
            });

        // Merge both collections and remove duplicates by id
        $assignedAdiutors = collect($adiutorsFromAssignments)->concat($adiutorsFromTasks)
            ->unique(function($adiutor) {
                return $adiutor['id'];
            })
            ->values();

        // Get task IDs that are already scheduled (separate auto vs manual)
        $allSchedules = \App\Models\TaskSchedule::whereIn('task_id', $project->tasks->pluck('taskID'))
            ->get();
        
        $scheduledTaskIds = $allSchedules->pluck('task_id')->toArray();
        $manuallyScheduledTaskIds = $allSchedules->where('schedule_type', 'manual')->pluck('task_id')->toArray();

        // Detect deadline conflicts BEFORE auto-scheduling
        $deadlineConflicts = [];
        $conflictDetails = []; // Store details about which task conflicts with which
        $tasksByAdiutorAndDeadline = $project->tasks
            ->filter(fn($t) => $t->deadline && $t->assignedTo)
            ->groupBy(function($task) {
                return $task->assignedTo . '_' . \Carbon\Carbon::parse($task->deadline)->format('Y-m-d');
            });
        
        foreach ($tasksByAdiutorAndDeadline as $group) {
            if ($group->count() > 1) {
                // Multiple tasks with same deadline for same adiutor = conflict
                // Sort by creation date (taskID) descending and skip the last one (oldest)
                $sortedGroup = $group->sortByDesc('taskID');
                $oldestTask = $sortedGroup->last(); // The task that will remain scheduled
                $sortedGroup->pop(); // Remove last task (oldest/first assigned)
                
                foreach ($sortedGroup as $conflictingTask) {
                    $deadlineConflicts[] = $conflictingTask->taskID;
                    
                    // Store which task it conflicts with
                    $conflictDetails[$conflictingTask->taskID] = [
                        'conflicting_task_id' => $oldestTask->taskID,
                        'conflicting_task_title' => $oldestTask->taskTitle,
                    ];
                    
                    // Delete any existing schedule for conflicting tasks
                    \App\Models\TaskSchedule::where('task_id', $conflictingTask->taskID)->delete();
                }
            }
        }

        // Auto-schedule assigned tasks that don't have a schedule yet AND are not conflicting
        foreach ($project->tasks as $task) {
            // Skip if task is in conflict list
            if (in_array($task->taskID, $deadlineConflicts)) {
                continue;
            }
            
            // If task has a deadline and is assigned to an adiutor but not scheduled yet, auto-create schedule
            if ($task->deadline &&
                $task->assignedTo && 
                $task->assignedUser && 
                $task->assignedUser->role === 'adiutor' && 
                !in_array($task->taskID, $scheduledTaskIds)) {
                
                // Calculate schedule times based on deadline
                $deadline = \Carbon\Carbon::parse($task->deadline);
                $endTime = $deadline->copy()->setTime(17, 0, 0); // 5 PM on deadline
                
                // Calculate start time (max_hours before, or 9 AM same day, whichever is later)
                $estimatedHours = min($task->max_hours ?? $task->estimated_hours ?? 8, 8); // Cap at 8 hours per day
                $startTime = $endTime->copy()->subHours($estimatedHours);
                
                // If start time is before 9 AM, set it to 9 AM same day
                if ($startTime->hour < 9) {
                    $startTime = $endTime->copy()->setTime(9, 0, 0);
                }
                
                // Create the schedule entry
                \App\Models\TaskSchedule::create([
                    'task_id' => $task->taskID,
                    'adiutor_id' => $task->assignedTo,
                    'scheduled_start' => $startTime,
                    'scheduled_end' => $endTime,
                    'estimated_duration_minutes' => $estimatedHours * 60,
                    'schedule_type' => 'auto',
                    'notes' => 'Automatically scheduled based on task deadline',
                ]);
                
                // Add to scheduled list so it won't appear in unscheduled
                $scheduledTaskIds[] = $task->taskID;
            }
        }

        // Filter unscheduled tasks: include tasks without deadlines OR tasks with deadline conflicts (unless manually scheduled)
        $unscheduledTasks = $project->tasks->filter(function($task) use ($scheduledTaskIds, $deadlineConflicts, $manuallyScheduledTaskIds) {
            // If manually scheduled, NEVER show in unscheduled (manual scheduling overrides conflicts)
            if (in_array($task->taskID, $manuallyScheduledTaskIds)) {
                return false;
            }
            
            // Include if has deadline conflict (not yet manually resolved)
            if (in_array($task->taskID, $deadlineConflicts)) {
                return true;
            }
            
            // Exclude if scheduled (and not a conflict)
            if (in_array($task->taskID, $scheduledTaskIds)) {
                return false;
            }
            
            // Include if no deadline (truly unscheduled)
            if (!$task->deadline) {
                return true;
            }
            
            // Include if not assigned to anyone
            if (!$task->assignedTo) {
                return true;
            }
            
            return false;
        })->map(function($task) use ($deadlineConflicts, $conflictDetails) {
            $taskData = [
                'id' => $task->taskID,
                'title' => $task->taskTitle,
                'description' => $task->description,
                'estimated_hours' => $task->estimated_hours ?? 0,
                'priority' => $task->priority,
                'deadline' => $task->deadline,
                'assigned_to' => $task->assignedUser ? $task->assignedUser->fullName : 'Unassigned',
                'assigned_to_id' => $task->assignedTo,
                'has_conflict' => in_array($task->taskID, $deadlineConflicts),
            ];
            
            // Add conflict details if this task has a conflict
            if (isset($conflictDetails[$task->taskID])) {
                $taskData['conflicting_with'] = $conflictDetails[$task->taskID]['conflicting_task_title'];
            }
            
            return $taskData;
        });

        return view('admin.projects.schedule', compact('project', 'assignedAdiutors', 'unscheduledTasks'));
    }
}