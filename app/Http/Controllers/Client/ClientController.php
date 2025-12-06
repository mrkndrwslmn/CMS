<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\DashboardStatsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Document;
use App\Models\Project;
use App\Models\ProjectFeedback;

class ClientController extends Controller
{
    use \App\Http\Controllers\Client\ClientMessagingMethods;
    
    protected DashboardStatsService $statsService;

    public function __construct(DashboardStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Show client dashboard
     * OPTIMIZED: Uses cached stats and optimized queries
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get cached dashboard statistics (reduces 4 count queries to 1 cached call)
        $stats = $this->statsService->getClientStats($user->id);

        // Get recent projects with optimized query - removed redundant unique() and take()
        $recentProjects = DB::table('projects')
            ->leftJoin('project_assignments', function($join) {
                $join->on('projects.id', '=', 'project_assignments.project_id')
                     ->whereNotIn('project_assignments.status', ['removed', 'declined']);
            })
            ->leftJoin('users', 'project_assignments.adiutor_id', '=', 'users.id')
            ->where('projects.client_id', $user->id)
            ->select(
                'projects.id',
                'projects.title',
                'projects.description',
                'projects.status',
                'projects.deadline',
                'project_assignments.progress_percentage',
                'users.fullName as adiutor_name',
                'projects.created_at'
            )
            ->groupBy('projects.id', 'projects.title', 'projects.description', 'projects.status', 
                      'projects.deadline', 'project_assignments.progress_percentage', 
                      'users.fullName', 'projects.created_at')
            ->orderBy('projects.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($project) {
                $project->created_at = Carbon::parse($project->created_at);
                if ($project->deadline) {
                    $project->deadline = Carbon::parse($project->deadline);
                }
                
                if ($project->adiutor_name) {
                    $project->assignedAdiutor = (object) [
                        'user' => (object) ['fullName' => $project->adiutor_name]
                    ];
                } else {
                    $project->assignedAdiutor = null;
                }
                return $project;
            });

        // Get recent service requests - simplified
        $recentRequests = DB::table('service_requests')
            ->where('client_id', $user->id)
            ->select('id', 'service_type', 'status', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($request) {
                $request->created_at = Carbon::parse($request->created_at);
                if ($request->updated_at) {
                    $request->updated_at = Carbon::parse($request->updated_at);
                }
                return $request;
            });

        // Get recent notifications - optimized with single query
        try {
            $recentMessages = DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('notifiable_type', 'App\\Models\\User')
                ->whereNull('read_at')
                ->select('id', 'type', 'data', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($notification) {
                    $notification->created_at = Carbon::parse($notification->created_at);
                    $data = is_string($notification->data) ? json_decode($notification->data, true) : (array)$notification->data;
                    $notification->message = $data['message'] ?? $data['title'] ?? 'New notification';
                    $notification->sender = (object) ['fullName' => $data['sender_name'] ?? 'System'];
                    return $notification;
                });
        } catch (\Exception $e) {
            $recentMessages = collect([]);
            \Log::error('Failed to load notifications: ' . $e->getMessage());
        }

        // Get upcoming deadlines - optimized
        $upcomingDeadlines = DB::table('projects')
            ->where('client_id', $user->id)
            ->whereNotNull('deadline')
            ->where('deadline', '>', now())
            ->where('status', '!=', 'completed')
            ->select('id', 'title', 'deadline', 'status', 'created_at')
            ->orderBy('deadline', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($project) {
                $project->created_at = Carbon::parse($project->created_at);
                $project->deadline = Carbon::parse($project->deadline);
                return $project;
            });
        
        // Get active announcements - cached for 5 minutes
        $announcements = Cache::remember("client_announcements_{$user->id}", 300, function () {
            return DB::table('announcements')
                ->join('users', 'announcements.created_by', '=', 'users.id')
                ->where('announcements.status', 'active')
                ->where(function ($query) {
                    $query->where('announcements.target_audience', 'LIKE', '%client%')
                          ->orWhere('announcements.target_audience', 'LIKE', '%all%');
                })
                ->where(function ($query) {
                    $query->whereNull('announcements.expires_at')
                          ->orWhere('announcements.expires_at', '>', now());
                })
                ->where(function ($query) {
                    $query->whereNull('announcements.starts_at')
                          ->orWhere('announcements.starts_at', '<=', now());
                })
                ->select(
                    'announcements.id', 'announcements.title', 'announcements.content',
                    'announcements.priority', 'announcements.status', 'announcements.starts_at',
                    'announcements.expires_at', 'announcements.created_at',
                    'users.fullName as creator_name'
                )
                ->orderByRaw("FIELD(announcements.priority, 'high', 'medium', 'low')")
                ->orderBy('announcements.created_at', 'desc')
                ->get()
                ->map(function ($announcement) {
                    $announcement->created_at = Carbon::parse($announcement->created_at);
                    if ($announcement->starts_at) {
                        $announcement->starts_at = Carbon::parse($announcement->starts_at);
                    }
                    if ($announcement->expires_at) {
                        $announcement->expires_at = Carbon::parse($announcement->expires_at);
                    }
                    $announcement->creator = (object) ['fullName' => $announcement->creator_name];
                    return $announcement;
                });
        });
        
        $recentProjects = collect($recentProjects);
        $recentMessages = collect($recentMessages);
        $upcomingDeadlines = collect($upcomingDeadlines);
        $announcements = collect($announcements);
        
        return view('client.dashboard', compact('user', 'stats', 'recentProjects', 'recentMessages', 'upcomingDeadlines', 'announcements'));
    }

    /**
     * Show client projects (index method)
     */
    public function index()
    {
        // Redirect to tasks for now since they serve the same purpose
        return $this->tasks();
    }

    /**
     * Show client tasks/projects
     */
    public function tasks()
    {
        $user = Auth::user();
        
        // Get all projects for this client with service request data
        $projects = DB::table('projects')
            ->leftJoin('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
            ->leftJoin('users', 'project_assignments.adiutor_id', '=', 'users.id')
            ->leftJoin('service_requests', 'projects.service_request_id', '=', 'service_requests.id')
            ->where('projects.client_id', $user->id)
            ->select(
                'projects.*',
                'project_assignments.id as assignment_id',
                'project_assignments.status as assignment_status',
                'project_assignments.progress_percentage',
                'project_assignments.start_date',
                'project_assignments.expected_completion',
                'users.fullName as adiutor_name',
                'users.email as adiutor_email',
                'service_requests.original_approved_budget',
                'service_requests.coupon_discount_amount',
                'service_requests.loyalty_discount_amount',
                'service_requests.applied_coupon_id'
            )
            ->orderBy('projects.created_at', 'desc')
            ->get()
            ->unique('id')
            ->map(function ($project) {
                // Convert date strings to Carbon instances
                $project->created_at = Carbon::parse($project->created_at);
                if ($project->updated_at) {
                    $project->updated_at = Carbon::parse($project->updated_at);
                }
                if ($project->deadline) {
                    $project->deadline = Carbon::parse($project->deadline);
                }
                if ($project->start_date) {
                    $project->start_date = Carbon::parse($project->start_date);
                }
                if ($project->expected_completion) {
                    $project->expected_completion = Carbon::parse($project->expected_completion);
                }

                // Create assignedAdiutor object structure that the view expects
                if ($project->adiutor_name) {
                    $project->assignedAdiutor = (object) [
                        'user' => (object) [
                            'fullName' => $project->adiutor_name,
                            'email' => $project->adiutor_email
                        ]
                    ];
                } else {
                    $project->assignedAdiutor = null;
                }

                // Calculate progress dynamically from completed tasks
                $projectModel = \App\Models\Project::find($project->id);
                $project->progress = $projectModel ? $projectModel->calculateProgressFromTasks() : 0;

                // Calculate if discounts were applied
                $project->hasDiscounts = ($project->coupon_discount_amount > 0 || $project->loyalty_discount_amount > 0);
                $project->originalBudget = $project->original_approved_budget ?? $project->budget;

                return $project;
            });

        return view('client.projects.index', compact('user', 'projects'));
    }

    /**
     * Show all documents for the client across all projects
     */
    public function documents(Request $request)
    {
        $user = Auth::user();
        
        // Build the base query for client's documents
        $query = DB::table('documents')
            ->leftJoin('tasks', 'documents.taskID', '=', 'tasks.taskID')
            ->leftJoin('projects', function($join) {
                $join->on('documents.project_id', '=', 'projects.id')
                     ->orOn('tasks.project_id', '=', 'projects.id');
            })
            ->leftJoin('project_milestones', 'tasks.phase_id', '=', 'project_milestones.id')
            ->leftJoin('users', 'documents.uploaded_by', '=', 'users.id')
            ->leftJoin('service_requests', 'projects.service_request_id', '=', 'service_requests.id')
            ->where(function($q) use ($user) {
                // Documents from projects owned by this client
                $q->where('projects.client_id', $user->id)
                  // Or documents directly assigned to this client
                  ->orWhere('documents.client_id', $user->id);
            })
            ->whereNull('documents.deleted_at')
            ->where(function($q) {
                $q->where('documents.is_archived', false)
                  ->orWhereNull('documents.is_archived');
            });
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('documents.fileName', 'like', "%{$search}%")
                  ->orWhere('documents.description', 'like', "%{$search}%")
                  ->orWhere('projects.title', 'like', "%{$search}%");
            });
        }
        
        // Project filter
        if ($request->filled('project')) {
            $query->where('projects.id', $request->project);
        }
        
        // File type filter
        if ($request->filled('type')) {
            $query->where('documents.fileType', $request->type);
        }
        
        $documents = $query->select(
                'documents.*',
                'projects.id as project_id',
                'projects.title as project_title',
                'projects.status as project_status',
                'tasks.taskTitle as task_name',
                'tasks.taskID as task_id',
                'tasks.phase_id',
                'project_milestones.phase_name',
                'project_milestones.is_paid as phase_is_paid',
                'users.fullName as uploaded_by_name',
                'service_requests.payment_type'
            )
            ->orderBy('documents.created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
        
        // Map documents to add lock status
        $documents->getCollection()->transform(function($doc) {
            $isLocked = false;
            
            // Check if document is locked based on payment status
            if ($doc->phase_id && $doc->payment_type === 'milestone_payment') {
                $isLocked = ($doc->phase_is_paid == 0 || $doc->phase_is_paid === false || $doc->phase_is_paid === null);
            }
            
            $doc->is_locked = $isLocked;
            $doc->created_at = Carbon::parse($doc->created_at);
            
            return $doc;
        });
        
        // Get client's projects for filter dropdown
        $projects = DB::table('projects')
            ->where('client_id', $user->id)
            ->orderBy('title')
            ->get(['id', 'title']);
        
        // Get stats
        $stats = [
            'total' => DB::table('documents')
                ->leftJoin('projects', 'documents.project_id', '=', 'projects.id')
                ->where(function($q) use ($user) {
                    $q->where('projects.client_id', $user->id)
                      ->orWhere('documents.client_id', $user->id);
                })
                ->whereNull('documents.deleted_at')
                ->count(),
            'total_size' => DB::table('documents')
                ->leftJoin('projects', 'documents.project_id', '=', 'projects.id')
                ->where(function($q) use ($user) {
                    $q->where('projects.client_id', $user->id)
                      ->orWhere('documents.client_id', $user->id);
                })
                ->whereNull('documents.deleted_at')
                ->sum('documents.fileSize'),
        ];
        
        return view('client.documents.index', compact('user', 'documents', 'projects', 'stats'));
    }

    /**
     * Show service requests
     */
    public function requests()
    {
        $user = Auth::user();
        
        // Get all service requests for this client
        $requests = DB::table('service_requests')
            ->leftJoin('users as approver', 'service_requests.approved_by', '=', 'approver.id')
            ->where('service_requests.client_id', $user->id)
            ->select(
                'service_requests.*',
                'approver.fullName as approved_by_name'
            )
            ->orderBy('service_requests.created_at', 'desc')
            ->get()
            ->map(function ($request) {
                // Convert date strings to Carbon instances
                $request->created_at = Carbon::parse($request->created_at);
                if ($request->updated_at) {
                    $request->updated_at = Carbon::parse($request->updated_at);
                }
                if ($request->deadline) {
                    $request->deadline = Carbon::parse($request->deadline);
                }
                return $request;
            });
        
        return view('client.requests.index', compact('user', 'requests'));
    }

    /**
     * Show feedback system
     */
    public function feedback()
    {
        $user = Auth::user();
        
        // Get completed projects with feedback status
        $completedProjects = Project::where('client_id', $user->id)
            ->where('status', 'completed')
            ->with(['assignments.adiutor'])
            ->get();

        // Get existing feedback project IDs
        $feedbackProjectIds = ProjectFeedback::where('client_id', $user->id)
            ->pluck('project_id')
            ->toArray();

        // Separate into pending and completed
        $pendingFeedback = $completedProjects->reject(function($project) use ($feedbackProjectIds) {
            return in_array($project->id, $feedbackProjectIds);
        });

        // Calculate feedback statistics
        $stats = [
            'completedProjects' => $completedProjects->count(),
            'reviewsGiven' => count($feedbackProjectIds),
            'pendingReviews' => $pendingFeedback->count(),
            'averageRating' => DB::table('feedbacks')
                ->where('client_id', $user->id)
                ->avg('rating') ?? 0
        ];
        
        // Format average rating to 1 decimal place
        $stats['averageRating'] = round($stats['averageRating'], 1);
        
        // Get feedback given by this client
        $completedFeedback = DB::table('feedbacks')
            ->join('projects', 'feedbacks.project_id', '=', 'projects.id')
            ->where('feedbacks.client_id', $user->id)
            ->select(
                'feedbacks.*',
                'projects.title as project_title'
            )
            ->orderBy('feedbacks.created_at', 'desc')
            ->get()
            ->map(function ($feedback) {
                // Convert date strings to Carbon instances
                $feedback->created_at = Carbon::parse($feedback->created_at);
                if ($feedback->updated_at) {
                    $feedback->updated_at = Carbon::parse($feedback->updated_at);
                }
                return $feedback;
            });
        
        return view('client.feedback.index', compact('user', 'stats', 'completedFeedback', 'pendingFeedback'));
    }

    /**
     * Show client profile
     */
    public function profile()
    {
        $user = Auth::user();
        
        // Get client profile if it exists
        $profile = DB::table('client_profiles')
            ->where('user_id', $user->id)
            ->first();
            
        return view('client.profile', compact('user', 'profile'));
    }

    /**
     * Update client profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
        ]);

        // Update user basic info
        $user->update([
            'fullName' => $request->input('fullName', $user->fullName),
            'email' => $request->input('email', $user->email),
        ]);

        // Update or create client profile
        DB::table('client_profiles')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'company_name' => $request->input('company_name'),
                'industry' => $request->input('industry'),
                'contact_phone' => $request->input('phone'), // Use contact_phone instead of phone
                'address' => $request->input('address'),
                'website' => $request->input('website'),
                // Note: bio, linkedin, twitter will be added after migration
                'updated_at' => now(),
            ]
        );

        return redirect()->route('client.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show project details
     */
    public function showProject($id)
    {
        $user = Auth::user();
        
        // Get project with related data
        $project = DB::table('projects')
            ->leftJoin('service_requests', 'projects.service_request_id', '=', 'service_requests.id')
            ->where('projects.id', $id)
            ->where('projects.client_id', $user->id)
            ->select(
                'projects.*',
                'service_requests.project_name as original_request_name',
                'service_requests.service_type'
            )
            ->first();

        if (!$project) {
            return redirect()->route('client.tasks')->with('error', 'Project not found.');
        }

        // Get project assignments (adiutors working on this project)
        $assignments = DB::table('project_assignments')
            ->join('users', 'project_assignments.adiutor_id', '=', 'users.id')
            ->leftJoin('adiutor_profiles', 'users.id', '=', 'adiutor_profiles.user_id')
            ->where('project_assignments.project_id', $id)
            ->select(
                'project_assignments.*',
                'users.fullName as adiutor_name',
                'users.email as adiutor_email',
                'adiutor_profiles.bio',
                'adiutor_profiles.title',
                'adiutor_profiles.portfolio_url'
            )
            ->get();

        // Get project tasks
        $tasks = DB::table('tasks')
            ->leftJoin('users', 'tasks.assignedTo', '=', 'users.id')
            ->where('tasks.project_id', $id)
            ->select(
                'tasks.*',
                'users.fullName as assigned_to_name'
            )
            ->orderBy('tasks.created_at', 'desc')
            ->get();

        // Get project feedback (project-based, not adiutor-specific)
        $feedback = DB::table('feedbacks')
            ->where('feedbacks.project_id', $id)
            ->where('feedbacks.client_id', $user->id)
            ->select('feedbacks.*')
            ->first();

        // Load service request for payment info
        $serviceRequest = null;
        if ($project->service_request_id) {
            $serviceRequest = \App\Models\ServiceRequest::with(['payments', 'project.milestones'])->find($project->service_request_id);
        }

        // Get tasks with their deliverables for grouped display
        // Only show approved deliverables OR non-deliverable documents for clients
        $tasksWithDeliverables = \App\Models\Task::where('project_id', $id)
            ->with(['documents' => function($query) {
                $query->where('is_archived', false)
                      ->where(function($q) {
                          $q->where('is_deliverable', false)
                            ->orWhere(function($sub) {
                                $sub->where('is_deliverable', true)
                                    ->where('is_approved', true);
                            });
                      })
                      ->with('uploader')
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
            ->where(function($q) {
                $q->where('is_deliverable', false)
                  ->orWhere(function($sub) {
                      $sub->where('is_deliverable', true)
                          ->where('is_approved', true);
                  });
            })
            ->with('uploader')
            ->orderByDesc('created_at')
            ->get();

        // Determine if documents are locked based on milestone payment
        $tasksWithDeliverables = $tasksWithDeliverables->map(function($task) use ($serviceRequest) {
            $task->documents = $task->documents->map(function($doc) use ($task, $serviceRequest) {
                $isLocked = false;
                if ($task->phase_id && $serviceRequest && $serviceRequest->payment_type === 'milestone_payment') {
                    $milestone = $serviceRequest->project->milestones->firstWhere('id', $task->phase_id);
                    if ($milestone && !$milestone->is_paid) {
                        $isLocked = true;
                    }
                }
                $doc->is_locked = $isLocked;
                return $doc;
            });
            return $task;
        });

        // Calculate project progress from completed tasks
        $projectModel = \App\Models\Project::find($project->id);
        $projectProgress = $projectModel ? $projectModel->calculateProgressFromTasks() : 0;
        $taskStats = $projectModel ? $projectModel->getTaskStats() : [
            'total' => 0,
            'pending' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'progress_percentage' => 0,
        ];

        return view('client.projects.show', compact('user', 'project', 'assignments', 'tasks', 'feedback', 'serviceRequest', 'tasksWithDeliverables', 'projectLevelDocuments', 'projectProgress', 'taskStats'));
    }

    /**
     * Download project document (with payment verification)
     */
    public function downloadDocument($projectId, $documentId)
    {
        $user = Auth::user();
        
        // Verify project belongs to client
        $project = DB::table('projects')
            ->where('id', $projectId)
            ->where('client_id', $user->id)
            ->first();
            
        if (!$project) {
            abort(404, 'Project not found.');
        }
        
        // Get service request for payment type check
        $serviceRequest = null;
        if ($project->service_request_id) {
            $serviceRequest = \App\Models\ServiceRequest::find($project->service_request_id);
        }
        
        // Get document with payment info
        $document = DB::table('documents')
            ->leftJoin('tasks', 'documents.taskID', '=', 'tasks.taskID')
            ->leftJoin('project_milestones', 'tasks.phase_id', '=', 'project_milestones.id')
            ->leftJoin('milestone_payments', 'project_milestones.id', '=', 'milestone_payments.milestone_id')
            ->where('documents.documentID', $documentId)
            ->where(function($query) use ($projectId) {
                $query->where('documents.project_id', $projectId)
                      ->orWhere('tasks.project_id', $projectId);
            })
            ->where('documents.is_archived', false)
            ->select(
                'documents.*',
                'tasks.phase_id',
                'milestone_payments.status as milestone_status',
                'milestone_payments.paid_at'
            )
            ->first();
            
        if (!$document) {
            abort(404, 'Document not found.');
        }
        
        // Check if document is locked
        $isLocked = false;
        if ($document->phase_id && $document->milestone_status) {
            $isLocked = $document->milestone_status !== 'paid' || !$document->paid_at;
        }
        if ($serviceRequest && $serviceRequest->payment_type === 'milestone_payment' && $document->phase_id && !$document->milestone_status) {
            $isLocked = true;
        }
        
        if ($isLocked) {
            return redirect()->back()
                ->withErrors(['error' => 'This document is locked. Please complete the required milestone payment to access it.']);
        }
        
        // Download file
        // Check if file is stored in R2 (has URL format) or local storage
        $isR2File = $document->filePath && (
            str_starts_with($document->filePath, 'https://') || 
            str_starts_with($document->filePath, 'http://')
        );
        
        if ($isR2File) {
            // File is in R2, redirect to the direct URL
            return redirect($document->filePath);
        } else {
            // Legacy: File is in local storage
            $filePath = storage_path('app/public/' . $document->filePath);
            
            if (!file_exists($filePath)) {
                abort(404, 'File not found on server.');
            }
            
            return response()->download($filePath, $document->fileName);
        }
    }
}