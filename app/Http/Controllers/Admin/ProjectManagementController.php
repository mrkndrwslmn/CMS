<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectMilestone;
use App\Models\ProjectTemplate;
use App\Models\GroupChat;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Task;
use App\Models\WalletTransaction;
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
     * OPTIMIZED: Uses aggregate queries instead of loading all records, caching for expensive calculations
     */
    public function show($id)
    {
        $project = Project::with(['serviceRequest', 'client', 'adiutors', 'tasks.assignedUser', 'assignments'])
                         ->findOrFail($id);

        // Calculate budget overview using aggregate queries (much faster than loading all records)
        $taskBudgetStats = \DB::table('tasks')
            ->where('project_id', $project->id)
            ->selectRaw('COALESCE(SUM(allocated_budget), 0) as total_allocated, COALESCE(SUM(actual_cost), 0) as total_spent')
            ->first();
        
        $totalAllocated = $taskBudgetStats->total_allocated ?? 0;
        $totalSpent = $taskBudgetStats->total_spent ?? 0;
        
        // Calculate Adiutor Earnings using aggregate queries (OPTIMIZED)
        // 1. Approved Time Entries (Hourly Rate) - aggregate query
        $approvedTimeStats = \DB::table('time_entries')
            ->join('tasks', 'time_entries.task_id', '=', 'tasks.taskID')
            ->where('tasks.project_id', $project->id)
            ->where('time_entries.is_approved', true)
            ->selectRaw('COALESCE(SUM(time_entries.calculated_amount), 0) as total_amount, COALESCE(SUM(time_entries.duration_minutes), 0) as total_minutes, COUNT(*) as entry_count')
            ->first();
        
        $hourlyEarnings = $approvedTimeStats->total_amount ?? 0;
        $totalApprovedHours = ($approvedTimeStats->total_minutes ?? 0) / 60;
        $approvedEntryCount = $approvedTimeStats->entry_count ?? 0;
        
        // 2. Approved Fixed Rate Payments - aggregate query
        $approvedFixedStats = \DB::table('project_assignments')
            ->where('project_id', $project->id)
            ->where('payment_type', 'fixed_rate')
            ->where('fixed_rate_approved', true)
            ->selectRaw('COALESCE(SUM(agreed_rate), 0) as total_amount, COUNT(*) as count')
            ->first();
        
        $fixedRateEarnings = $approvedFixedStats->total_amount ?? 0;
        $approvedFixedCount = $approvedFixedStats->count ?? 0;
        
        // 3. Pending Fixed Rates - aggregate query
        $pendingFixedStats = \DB::table('project_assignments')
            ->where('project_id', $project->id)
            ->where('payment_type', 'fixed_rate')
            ->where('fixed_rate_approved', false)
            ->whereNotIn('status', ['removed', 'declined'])
            ->selectRaw('COALESCE(SUM(agreed_rate), 0) as total_amount, COUNT(*) as count')
            ->first();
        
        $pendingFixedRateAmount = $pendingFixedStats->total_amount ?? 0;
        $pendingFixedCount = $pendingFixedStats->count ?? 0;
        
        // 4. Pending Time Entries - aggregate query
        $pendingTimeStats = \DB::table('time_entries')
            ->join('tasks', 'time_entries.task_id', '=', 'tasks.taskID')
            ->where('tasks.project_id', $project->id)
            ->where('time_entries.is_approved', false)
            ->selectRaw('COALESCE(SUM(time_entries.calculated_amount), 0) as total_amount')
            ->first();
        
        $pendingHourlyAmount = $pendingTimeStats->total_amount ?? 0;
        
        // Total Adiutor Earnings (Approved)
        $totalAdiutorEarnings = $hourlyEarnings + $fixedRateEarnings;
        
        // Total Pending Earnings
        $totalPendingEarnings = $pendingHourlyAmount + $pendingFixedRateAmount;
        
        // Remaining budget after earnings
        $remainingBudget = ($project->budget ?? 0) - $totalAllocated;
        $remainingAfterEarnings = ($project->budget ?? 0) - $totalAdiutorEarnings;
        $projectedRemaining = $remainingAfterEarnings - $totalPendingEarnings;

        $budgetOverview = [
            'project_budget' => $project->budget,
            'total_allocated' => $totalAllocated,
            'total_spent' => $totalSpent,
            'remaining_budget' => $remainingBudget,
            'is_over_budget' => $remainingBudget < 0,
            'budget_utilization_percentage' => $project->budget > 0 ? round(($totalAllocated / $project->budget) * 100, 2) : 0,
            
            // Phase 2: Adiutor Earnings Enhancement
            'adiutor_earnings' => [
                'hourly' => [
                    'approved_amount' => $hourlyEarnings,
                    'approved_hours' => round($totalApprovedHours, 2),
                    'pending_amount' => $pendingHourlyAmount,
                    'entry_count' => $approvedEntryCount,
                ],
                'fixed_rate' => [
                    'approved_amount' => $fixedRateEarnings,
                    'approved_count' => $approvedFixedCount,
                    'pending_amount' => $pendingFixedRateAmount,
                    'pending_count' => $pendingFixedCount,
                ],
                'total_approved' => $totalAdiutorEarnings,
                'total_pending' => $totalPendingEarnings,
                'remaining_after_earnings' => $remainingAfterEarnings,
                'projected_remaining' => $projectedRemaining,
                'earnings_percentage' => $project->budget > 0 ? round(($totalAdiutorEarnings / $project->budget) * 100, 2) : 0,
            ],
        ];

        // Get available adiutors - OPTIMIZED with caching
        $projectServiceType = $project->serviceRequest ? $project->serviceRequest->service_type : null;
        $assignedAdiutorIds = $project->adiutors->pluck('id')->toArray();

        // Cache available adiutors calculation for 2 minutes (it's expensive)
        $cacheKey = "project_{$id}_available_adiutors_" . md5(json_encode($assignedAdiutorIds) . $projectServiceType);
        $availableAdiutors = \Cache::remember($cacheKey, now()->addMinutes(2), function () use ($assignedAdiutorIds, $projectServiceType) {
            return $this->calculateAvailableAdiutors($assignedAdiutorIds, $projectServiceType);
        });

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

        return view('admin.projects.show', compact('project', 'budgetOverview', 'availableAdiutors', 'tasksWithDeliverables', 'projectLevelDocuments', 'totalDeliverables', 'pendingDeliverables'));
    }

    /**
     * Calculate available adiutors with ranking scores
     * Extracted to a separate method for caching
     */
    private function calculateAvailableAdiutors(array $assignedAdiutorIds, ?string $projectServiceType): \Illuminate\Support\Collection
    {
        // Get active project counts in a single query for all adiutors
        $activeProjectCounts = \DB::table('project_assignments')
            ->select('adiutor_id', \DB::raw('COUNT(*) as count'))
            ->whereIn('status', ['assigned', 'active', 'in_progress'])
            ->groupBy('adiutor_id')
            ->pluck('count', 'adiutor_id');

        $adiutors = User::where('role', 'adiutor')
            ->when(!empty($assignedAdiutorIds), function($q) use ($assignedAdiutorIds) {
                $q->whereNotIn('id', $assignedAdiutorIds);
            })
            ->with([
                'calendarIntegration',
                'adiutorProfile.skills',
            ])
            ->get();

        return $adiutors->map(function ($adiutor) use ($projectServiceType, $activeProjectCounts) {
            $skills = $adiutor->adiutorProfile ? $adiutor->adiutorProfile->skills : collect();
            $activeProjectsCount = $activeProjectCounts[$adiutor->id] ?? 0;
            
            // Calculate workload score
            $workloadScore = match(true) {
                $activeProjectsCount == 0 => 30,
                $activeProjectsCount <= 2 => 24,
                $activeProjectsCount <= 4 => 18,
                $activeProjectsCount <= 6 => 12,
                default => 6,
            };

            // Calculate skill score
            $contrib = 0.0;
            $skillScore = 0;
            
            if ($projectServiceType && $skills->isNotEmpty()) {
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
                $relevantKeywords = [];
                
                foreach ($skillCategories as $category => $keywords) {
                    if (str_contains($normalizedServiceType, $category) || $category === $normalizedServiceType) {
                        $relevantKeywords = array_merge($relevantKeywords, $keywords);
                    }
                }
                
                if (empty($relevantKeywords)) {
                    $relevantKeywords[] = str_replace(['-', '_', ' '], '', $normalizedServiceType);
                }
                
                foreach ($skills as $skill) {
                    $skillName = strtolower(str_replace(['-', '_', ' ', '/'], '', $skill->name));
                    
                    foreach ($relevantKeywords as $keyword) {
                        $normalizedKeyword = str_replace(['-', '_', ' ', '/'], '', $keyword);
                        if (str_contains($skillName, $normalizedKeyword) || str_contains($normalizedKeyword, $skillName)) {
                            $prof = 3;
                            if (isset($skill->pivot->proficiency_level)) {
                                $p = $skill->pivot->proficiency_level;
                                if (is_numeric($p)) {
                                    $prof = max(1, min(5, (int)$p));
                                } else {
                                    $pl = strtolower(trim($p));
                                    $prof = match(true) {
                                        in_array($pl, ['beginner','junior']) => 2,
                                        in_array($pl, ['intermediate','mid']) => 3,
                                        in_array($pl, ['advanced','senior','expert']) => 5,
                                        default => 3,
                                    };
                                }
                            }

                            $years = isset($skill->pivot->years_experience) && is_numeric($skill->pivot->years_experience) 
                                ? max(0, min(20, (float)$skill->pivot->years_experience)) 
                                : 0;

                            $contrib += ($prof / 5) * (1 + ($years / 10));
                            break;
                        }
                    }
                }

                $skillScore = min($contrib * 20, 70);
            } elseif ($skills->isNotEmpty()) {
                $skillScore = 20;
            }

            $score = round($skillScore + $workloadScore, 2);
            
            return [
                'id' => $adiutor->id,
                'fullName' => $adiutor->fullName,
                'email' => $adiutor->email,
                'calendar_connected' => $adiutor->calendarIntegration && $adiutor->calendarIntegration->is_connected,
                'skills' => $skills,
                'rating' => $adiutor->calculateAdiutorRating() ?? 0,
                'active_projects_count' => $activeProjectsCount,
                'rank_score' => $score,
                'skill_score' => $skillScore,
                'workload_score' => $workloadScore,
                'has_matching_skill' => ($contrib > 0),
            ];
        })
        ->sortByDesc('rank_score')
        ->values();
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
        
        // Get active project templates for pre-populating project data
        $templates = ProjectTemplate::active()
                                    ->orderBy('category')
                                    ->orderBy('name')
                                    ->get();
        
        return view('admin.projects.create', compact('clients', 'serviceRequests', 'templates'));
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id|unique:projects,service_request_id',
            'template_id' => 'nullable|exists:project_templates,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'budget' => 'required|numeric|min:0',
            'budget_type' => 'required|in:fixed,hourly',
            'deadline' => 'nullable|date|after:today',
            'priority' => 'required|in:low,medium,high,urgent',
            'requirements' => 'nullable|json',
            'skills_required' => 'nullable|json',
            'apply_template_tasks' => 'nullable|boolean',
            'apply_template_milestones' => 'nullable|boolean',
        ]);

        // Get service request to get client_id
        $serviceRequest = ServiceRequest::findOrFail($request->service_request_id);
        
        // Get template if selected
        $template = $request->filled('template_id') 
            ? ProjectTemplate::find($request->template_id) 
            : null;

        // Use template skills if available and no custom skills provided
        $skillsRequired = $request->skills_required 
            ? json_decode($request->skills_required) 
            : ($template && $template->skills_required ? $template->skills_required : null);

        // Use template requirements if available and no custom requirements provided
        $requirements = $request->requirements 
            ? json_decode($request->requirements) 
            : ($template && $template->requirements_template ? [$template->requirements_template] : null);

        return DB::transaction(function () use ($request, $serviceRequest, $template, $skillsRequired, $requirements) {
            $project = Project::create([
                'service_request_id' => $request->service_request_id,
                'client_id' => $serviceRequest->client_id,
                'title' => $request->title,
                'description' => $request->description,
                'budget' => $request->budget,
                'budget_type' => $request->budget_type ?? ($template ? $template->budget_type : 'fixed'),
                'deadline' => $request->deadline,
                'priority' => $request->priority,
                'requirements' => $requirements,
                'skills_required' => $skillsRequired,
                'status' => 'active',
                'started_at' => now(),
            ]);

            // Apply template tasks if template selected and option enabled
            if ($template && $request->boolean('apply_template_tasks') && !empty($template->default_tasks)) {
                $this->applyTemplateTasks($project, $template, $serviceRequest);
            }

            // Apply template milestones if template selected and option enabled
            if ($template && $request->boolean('apply_template_milestones') && !empty($template->milestones_template)) {
                $this->applyTemplateMilestones($project, $template, $serviceRequest);
            }

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

            $successMessage = 'Project created successfully.';
            if ($template) {
                $appliedItems = [];
                if ($request->boolean('apply_template_tasks') && !empty($template->default_tasks)) {
                    $appliedItems[] = count($template->default_tasks) . ' tasks';
                }
                if ($request->boolean('apply_template_milestones') && !empty($template->milestones_template)) {
                    $appliedItems[] = count($template->milestones_template) . ' milestones';
                }
                if (!empty($appliedItems)) {
                    $successMessage .= ' Applied template with ' . implode(' and ', $appliedItems) . '.';
                }
            }

            return redirect()->route('admin.projects.show', $project->id)
                            ->with('success', $successMessage);
        });
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
            'force_complete' => 'nullable|boolean',
        ]);

        $project = Project::with(['client', 'tasks'])->findOrFail($id);
        
        // Check for incomplete tasks unless force_complete is set
        if (!$request->boolean('force_complete')) {
            $incompleteTasks = $project->tasks->whereNotIn('status', ['completed', 'cancelled']);
            
            if ($incompleteTasks->count() > 0) {
                $taskTitles = $incompleteTasks->take(5)->pluck('taskTitle')->implode(', ');
                $remaining = $incompleteTasks->count() > 5 ? ' and ' . ($incompleteTasks->count() - 5) . ' more' : '';
                
                return redirect()->back()
                    ->with('error', "Cannot complete project: {$incompleteTasks->count()} task(s) are still incomplete ({$taskTitles}{$remaining}). Complete all tasks first or use 'Force Complete' option.");
            }
        }
        
        // Update project to completed status
        $project->update([
            'status' => 'completed',
            'completed_at' => now(),
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
            'force_complete' => 'nullable|boolean',
        ]);

        $project = Project::with(['client', 'adiutors', 'tasks'])->findOrFail($id);
        $oldStatus = $project->status;
        
        // Validate task completion when marking as completed (unless force_complete)
        if ($request->status === 'completed' && $project->status !== 'completed' && !$request->boolean('force_complete')) {
            $incompleteTasks = $project->tasks->whereNotIn('status', ['completed', 'cancelled']);
            
            if ($incompleteTasks->count() > 0) {
                $taskTitles = $incompleteTasks->take(3)->pluck('taskTitle')->implode(', ');
                $remaining = $incompleteTasks->count() > 3 ? ' (+' . ($incompleteTasks->count() - 3) . ' more)' : '';
                
                return redirect()->back()
                    ->with('error', "Cannot mark as completed: {$incompleteTasks->count()} incomplete task(s): {$taskTitles}{$remaining}. Complete all tasks first or check 'Force Complete'.");
            }
        }
        
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
            'payment_type' => 'required|in:fixed_rate,hourly_rate',
            'hourly_rate' => 'nullable|numeric|min:0|required_if:payment_type,hourly_rate',
            'max_hours' => 'nullable|numeric|min:0.5',
            'requires_time_tracking' => 'nullable|boolean',
            'agreed_rate' => 'nullable|numeric|min:0|required_if:payment_type,fixed_rate',
            'expected_completion' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $project = Project::findOrFail($id);
        $adiutorId = $request->adiutor_id;

        // Use database transaction with pessimistic locking to prevent race conditions
        // This ensures atomic check-then-create/update operations for adiutor assignments
        return DB::transaction(function () use ($request, $id, $project, $adiutorId) {
            // Lock existing assignment rows to prevent concurrent modifications
            $existingAssignment = ProjectAssignment::where('project_id', $id)
                ->where('adiutor_id', $adiutorId)
                ->lockForUpdate()
                ->first();
                
            if ($existingAssignment && !in_array($existingAssignment->status, ['removed', 'declined'])) {
                return redirect()->back()->with('error', 'Adiutor is already assigned to this project.');
            }

            // Prepare assignment data based on payment type
            $assignmentData = [
                'payment_type' => $request->payment_type,
                'start_date' => now(),
                'expected_completion' => $request->expected_completion,
                'status' => 'assigned',
                'notes' => $request->notes,
            ];

            // Set payment-specific fields based on payment type
            if ($request->payment_type === 'fixed_rate') {
                $assignmentData['agreed_rate'] = $request->agreed_rate;
                $assignmentData['hourly_rate'] = null;
                $assignmentData['max_hours'] = null; // Fixed rate doesn't need max hours
                $assignmentData['requires_time_tracking'] = false;
            } else {
                $assignmentData['hourly_rate'] = $request->hourly_rate;
                $assignmentData['max_hours'] = $request->max_hours; // Optional max hours limit
                $assignmentData['agreed_rate'] = null;
                $assignmentData['requires_time_tracking'] = true;
            }

            // If there's a removed/declined assignment, update it; otherwise create new
            if ($existingAssignment && in_array($existingAssignment->status, ['removed', 'declined'])) {
                // Update existing assignment
                $existingAssignment->update($assignmentData);
                
                // Manually add to group chat since update doesn't trigger created event
                $groupChat = GroupChat::getOrCreateForProject($id);
                $groupChat->addMember($adiutorId);
            } else {
                // Create new assignment (will automatically add to group chat via model boot method)
                $assignmentData['project_id'] = $id;
                $assignmentData['adiutor_id'] = $adiutorId;
                ProjectAssignment::create($assignmentData);
            }

            // Send project assignment email (outside of critical section but still in transaction)
            try {
                $adiutor = User::find($adiutorId);
                $assignedBy = Auth::user();
                
                if ($adiutor && $adiutor->email) {
                    Mail::to($adiutor->email)->send(new ProjectAssigned($project, $adiutor, $assignedBy));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send project assignment email', [
                    'project_id' => $id,
                    'adiutor_id' => $adiutorId,
                    'error' => $e->getMessage()
                ]);
                // Don't rollback transaction for email failure - assignment should still succeed
            }

            $paymentLabel = $request->payment_type === 'fixed_rate' ? 'Fixed Rate' : 'Hourly Rate';
            $maxHoursNote = $request->max_hours ? " with {$request->max_hours} hour limit" : '';
            return redirect()->back()->with('success', "Adiutor assigned to project with {$paymentLabel} payment{$maxHoursNote} successfully.");
        });
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
                    
                    // Delete any existing AUTO schedule for conflicting tasks (keep manual schedules)
                    \App\Models\TaskSchedule::where('task_id', $conflictingTask->taskID)
                        ->where('schedule_type', 'auto')
                        ->delete();
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
                
                // No additional conflict check needed here - deadline conflicts already detected above
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

    /**
     * Approve fixed rate payment for an adiutor assignment
     * 
     * This is for assignments with payment_type = 'fixed_rate'
     * When approved, the adiutor becomes eligible to receive the agreed_rate amount in their payout
     */
    public function approveFixedRate(Request $request, $projectId, $assignmentId)
    {
        $project = Project::findOrFail($projectId);
        $assignment = ProjectAssignment::where('id', $assignmentId)
            ->where('project_id', $projectId)
            ->firstOrFail();

        // Validate payment type is fixed rate
        if (!$assignment->isFixedRate()) {
            return redirect()->back()
                ->with('error', 'This assignment uses hourly rate payment, not fixed rate. Time entries must be approved individually.');
        }

        // Validate assignment status (must be completed or at least in active status)
        if (!in_array($assignment->status, ['completed', 'active', 'assigned'])) {
            return redirect()->back()
                ->with('error', 'Assignment must be active or completed to approve fixed rate payment.');
        }

        // Check if already approved
        if ($assignment->fixed_rate_approved) {
            return redirect()->back()
                ->with('error', 'Fixed rate has already been approved for this assignment.');
        }

        // Validate agreed_rate exists and is greater than 0
        if (!$assignment->agreed_rate || $assignment->agreed_rate <= 0) {
            return redirect()->back()
                ->with('error', 'No agreed rate set for this assignment. Please edit the assignment first.');
        }

        try {
            DB::transaction(function() use ($assignment) {
                // Mark as approved
                $assignment->update([
                    'fixed_rate_approved' => true,
                    'fixed_rate_approved_at' => now(),
                    'fixed_rate_approved_by' => Auth::id(),
                ]);

                // Add earnings to adiutor's wallet
                $adiutor = $assignment->adiutor;
                $amount = $assignment->agreed_rate;
                
                if ($amount > 0 && $adiutor) {
                    $adiutor->addWorkEarnings(
                        $amount,
                        WalletTransaction::SOURCE_FIXED_RATE,
                        $assignment->id,
                        "Fixed rate payment for project: " . ($assignment->project?->title ?? 'Project'),
                        Auth::id(),
                        [
                            'project_id' => $assignment->project_id,
                            'agreed_rate' => $assignment->agreed_rate,
                        ]
                    );
                }

                // Log the approval
                \Log::info('Fixed rate approved for assignment', [
                    'assignment_id' => $assignment->id,
                    'project_id' => $assignment->project_id,
                    'adiutor_id' => $assignment->adiutor_id,
                    'agreed_rate' => $assignment->agreed_rate,
                    'approved_by' => Auth::id(),
                ]);
            });

            $adiutor = $assignment->adiutor;
            $formattedAmount = '₱' . number_format($assignment->agreed_rate, 2);

            return redirect()->back()
                ->with('success', "Fixed rate payment of {$formattedAmount} approved for {$adiutor->fullName}. This amount is now available for payout.");

        } catch (\Exception $e) {
            \Log::error('Failed to approve fixed rate', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to approve fixed rate payment. Please try again.');
        }
    }

    /**
     * Revoke fixed rate approval (in case of mistake)
     */
    public function revokeFixedRateApproval(Request $request, $projectId, $assignmentId)
    {
        $project = Project::findOrFail($projectId);
        $assignment = ProjectAssignment::where('id', $assignmentId)
            ->where('project_id', $projectId)
            ->firstOrFail();

        // Validate it's a fixed rate assignment
        if (!$assignment->isFixedRate()) {
            return redirect()->back()
                ->with('error', 'This assignment does not use fixed rate payment.');
        }

        // Check if already paid
        if ($assignment->fixed_rate_paid) {
            return redirect()->back()
                ->with('error', 'Cannot revoke approval - payment has already been processed.');
        }

        // Check if not approved
        if (!$assignment->fixed_rate_approved) {
            return redirect()->back()
                ->with('error', 'Fixed rate has not been approved yet.');
        }

        try {
            DB::transaction(function() use ($assignment) {
                $assignment->update([
                    'fixed_rate_approved' => false,
                    'fixed_rate_approved_at' => null,
                    'fixed_rate_approved_by' => null,
                ]);

                \Log::info('Fixed rate approval revoked', [
                    'assignment_id' => $assignment->id,
                    'revoked_by' => Auth::id(),
                ]);
            });

            return redirect()->back()
                ->with('success', 'Fixed rate approval has been revoked.');

        } catch (\Exception $e) {
            \Log::error('Failed to revoke fixed rate approval', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to revoke approval. Please try again.');
        }
    }

    /**
     * Update assignment payment type and rate
     */
    public function updateAssignmentPayment(Request $request, $projectId, $assignmentId)
    {
        $request->validate([
            'payment_type' => 'required|in:fixed_rate,hourly_rate',
            'agreed_rate' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $project = Project::findOrFail($projectId);
        $assignment = ProjectAssignment::where('id', $assignmentId)
            ->where('project_id', $projectId)
            ->firstOrFail();

        // Cannot change payment type if already has approved payments
        if ($assignment->fixed_rate_approved || $assignment->fixed_rate_paid) {
            return redirect()->back()
                ->with('error', 'Cannot change payment type - fixed rate has already been approved or paid.');
        }

        // Check for approved hourly entries
        $hasApprovedHourlyEntries = $assignment->timeEntries()->where('is_approved', true)->exists();
        if ($hasApprovedHourlyEntries) {
            return redirect()->back()
                ->with('error', 'Cannot change payment type - there are already approved hourly time entries.');
        }

        $updateData = [
            'payment_type' => $request->payment_type,
        ];

        // Set appropriate rate based on payment type
        if ($request->payment_type === 'fixed_rate') {
            $updateData['agreed_rate'] = $request->agreed_rate;
            $updateData['requires_time_tracking'] = false;
        } else {
            $updateData['hourly_rate'] = $request->hourly_rate;
            $updateData['requires_time_tracking'] = true;
        }

        $assignment->update($updateData);

        return redirect()->back()
            ->with('success', 'Assignment payment settings updated successfully.');
    }

    /**
     * Apply template tasks to a newly created project
     * 
     * @param Project $project
     * @param ProjectTemplate $template
     * @param ServiceRequest $serviceRequest
     * @return void
     */
    private function applyTemplateTasks(Project $project, ProjectTemplate $template, ServiceRequest $serviceRequest): void
    {
        foreach ($template->default_tasks as $index => $taskData) {
            Task::create([
                'project_id' => $project->id,
                'service_request_id' => $serviceRequest->id,
                'client_id' => $serviceRequest->client_id,
                'title' => $taskData['title'],
                'description' => $taskData['description'] ?? '',
                'priority' => $taskData['priority'] ?? 'medium',
                'estimated_hours' => $taskData['estimated_hours'] ?? null,
                'status' => 'pending',
                'order' => $index + 1,
                'created_by' => Auth::id(),
            ]);
        }

        \Log::info('Applied template tasks to project', [
            'project_id' => $project->id,
            'template_id' => $template->id,
            'tasks_created' => count($template->default_tasks),
        ]);
    }

    /**
     * Apply template milestones to a newly created project
     * 
     * @param Project $project
     * @param ProjectTemplate $template
     * @param ServiceRequest $serviceRequest
     * @return void
     */
    private function applyTemplateMilestones(Project $project, ProjectTemplate $template, ServiceRequest $serviceRequest): void
    {
        $phaseOrder = 1;
        
        foreach ($template->milestones_template as $milestoneData) {
            // Calculate amount based on project budget and milestone percentage
            $percentage = $milestoneData['percentage'] ?? 0;
            $amount = ($project->budget * $percentage) / 100;

            ProjectMilestone::create([
                'project_id' => $project->id,
                'service_request_id' => $serviceRequest->id,
                'phase_name' => $milestoneData['phase_name'],
                'phase_order' => $phaseOrder,
                'percentage' => $percentage,
                'amount' => $amount,
                'description' => $milestoneData['description'] ?? null,
                'is_paid' => false,
            ]);

            $phaseOrder++;
        }

        \Log::info('Applied template milestones to project', [
            'project_id' => $project->id,
            'template_id' => $template->id,
            'milestones_created' => count($template->milestones_template),
        ]);
    }

    /**
     * Get time entries for a specific adiutor on a project
     * 
     * @param Request $request
     * @param Project $project
     * @param User $adiutor
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdiutorTimeEntries(Request $request, Project $project, User $adiutor)
    {
        try {
            // Get time entries for this adiutor on this project
            $timeEntries = \App\Models\TimeEntry::where('adiutor_id', $adiutor->id)
                ->where('project_id', $project->id)
                ->with(['task', 'approver:id,fullName'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($entry) {
                    return [
                        'id' => $entry->id,
                        'task_title' => $entry->task?->taskTitle ?? 'No Task',
                        'description' => $entry->description ?? '',
                        'duration_minutes' => $entry->duration_minutes ?? 0,
                        'hours' => round(($entry->duration_minutes ?? 0) / 60, 2),
                        'hourly_rate' => (float) ($entry->hourly_rate ?? 0),
                        'calculated_amount' => (float) ($entry->calculated_amount ?? 0),
                        'is_approved' => (bool) $entry->is_approved,
                        'is_billable' => (bool) ($entry->billable_minutes > 0),
                        'approved_by' => $entry->approver?->fullName,
                        'approved_at' => $entry->approved_at?->format('M d, Y H:i'),
                        'entry_date' => $entry->start_time?->format('M d, Y') ?? $entry->created_at?->format('M d, Y'),
                        'created_at' => $entry->created_at?->format('M d, Y H:i'),
                    ];
                });

            // Get assignment info
            $assignment = ProjectAssignment::where('project_id', $project->id)
                ->where('adiutor_id', $adiutor->id)
                ->first();

            // Summary stats
            $stats = [
                'total_entries' => $timeEntries->count(),
                'total_hours' => (float) $timeEntries->sum('hours'),
                'pending_count' => $timeEntries->where('is_approved', false)->count(),
                'pending_hours' => (float) $timeEntries->where('is_approved', false)->sum('hours'),
                'pending_amount' => (float) $timeEntries->where('is_approved', false)->sum('calculated_amount'),
                'approved_count' => $timeEntries->where('is_approved', true)->count(),
                'approved_hours' => (float) $timeEntries->where('is_approved', true)->sum('hours'),
                'approved_amount' => (float) $timeEntries->where('is_approved', true)->sum('calculated_amount'),
                'hourly_rate' => (float) ($assignment?->hourly_rate ?? $assignment?->agreed_rate ?? $adiutor->hourlyRate ?? 0),
                'max_hours' => $assignment?->max_hours,
            ];

            return response()->json([
                'success' => true,
                'adiutor' => [
                    'id' => $adiutor->id,
                    'name' => $adiutor->fullName,
                    'email' => $adiutor->email,
                ],
                'project' => [
                    'id' => $project->id,
                    'title' => $project->project_title,
                ],
                'time_entries' => $timeEntries->values(),
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to get adiutor time entries', [
                'project_id' => $project->id,
                'adiutor_id' => $adiutor->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load time entries: ' . $e->getMessage(),
            ], 500);
        }
    }
}