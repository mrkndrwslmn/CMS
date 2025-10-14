<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Show client dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get dashboard statistics
        $stats = [
            'activeProjects' => DB::table('projects')
                ->where('client_id', $user->id)
                ->where('status', 'in_progress')
                ->count(),
            'completedProjects' => DB::table('projects')
                ->where('client_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'pendingRequests' => DB::table('service_requests')
                ->where('client_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'totalSpent' => DB::table('projects')
                ->join('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
                ->where('projects.client_id', $user->id)
                ->where('project_assignments.status', 'completed')
                ->sum('project_assignments.agreed_rate') ?? 0,
        ];

        // Get recent projects
        $recentProjects = DB::table('projects')
            ->leftJoin('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
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
            ->orderBy('projects.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($project) {
                // Convert date strings to Carbon instances
                $project->created_at = Carbon::parse($project->created_at);
                if ($project->deadline) {
                    $project->deadline = Carbon::parse($project->deadline);
                }
                
                // Create assignedAdiutor object structure that the view expects
                if ($project->adiutor_name) {
                    $project->assignedAdiutor = (object) [
                        'user' => (object) [
                            'fullName' => $project->adiutor_name
                        ]
                    ];
                } else {
                    $project->assignedAdiutor = null;
                }
                return $project;
            });

        // Get recent service requests
        $recentRequests = DB::table('service_requests')
            ->where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($request) {
                // Convert date strings to Carbon instances
                $request->created_at = Carbon::parse($request->created_at);
                if ($request->updated_at) {
                    $request->updated_at = Carbon::parse($request->updated_at);
                }
                return $request;
            });

        // Get recent messages (using notifications as messages for now)
        $recentMessages = DB::table('notifications')
            ->where('notifications.user_id', $user->id)
            ->where('notifications.is_read', false)
            ->select(
                'notifications.message',
                'notifications.created_at'
            )
            ->orderBy('notifications.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                // Convert date string to Carbon instance
                $notification->created_at = Carbon::parse($notification->created_at);
                
                // Create a mock sender object structure that the view expects
                $notification->sender = (object) [
                    'fullName' => 'System'  // Since notifications table doesn't have created_by
                ];
                return $notification;
            });

        // Get upcoming deadlines
        $upcomingDeadlines = DB::table('projects')
            ->where('client_id', $user->id)
            ->whereNotNull('deadline')
            ->where('deadline', '>', now())
            ->where('status', '!=', 'completed')
            ->orderBy('deadline', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($project) {
                // Convert date strings to Carbon instances
                $project->created_at = Carbon::parse($project->created_at);
                $project->deadline = Carbon::parse($project->deadline);
                return $project;
            });
        
        // Ensure collections are always defined (convert to collections if they aren't already)
        $recentProjects = collect($recentProjects);
        $recentMessages = collect($recentMessages);
        $upcomingDeadlines = collect($upcomingDeadlines);
        
        return view('client.dashboard', compact('user', 'stats', 'recentProjects', 'recentMessages', 'upcomingDeadlines'));
    }

    /**
     * Show client tasks/projects
     */
    public function tasks()
    {
        $user = Auth::user();
        
        // Get all projects for this client
        $projects = DB::table('projects')
            ->leftJoin('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
            ->leftJoin('users', 'project_assignments.adiutor_id', '=', 'users.id')
            ->where('projects.client_id', $user->id)
            ->select(
                'projects.*',
                'project_assignments.id as assignment_id',
                'project_assignments.status as assignment_status',
                'project_assignments.progress_percentage',
                'project_assignments.start_date',
                'project_assignments.expected_completion',
                'users.fullName as adiutor_name',
                'users.email as adiutor_email'
            )
            ->orderBy('projects.created_at', 'desc')
            ->get()
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

                // Set progress from progress_percentage
                $project->progress = $project->progress_percentage;

                return $project;
            });
        
        return view('client.tasks', compact('user', 'projects'));
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
        
        // Get feedback given by this client
        $givenFeedback = DB::table('project_feedback')
            ->join('projects', 'project_feedback.project_id', '=', 'projects.id')
            ->join('users', 'project_feedback.adiutor_id', '=', 'users.id')
            ->where('project_feedback.client_id', $user->id)
            ->select(
                'project_feedback.*',
                'projects.title as project_title',
                'users.fullName as adiutor_name'
            )
            ->orderBy('project_feedback.created_at', 'desc')
            ->get();

        // Get projects that need feedback
        $pendingFeedback = DB::table('projects')
            ->join('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
            ->join('users', 'project_assignments.adiutor_id', '=', 'users.id')
            ->leftJoin('project_feedback', function($join) use ($user) {
                $join->on('projects.id', '=', 'project_feedback.project_id')
                     ->where('project_feedback.client_id', '=', $user->id);
            })
            ->where('projects.client_id', $user->id)
            ->where('project_assignments.status', 'completed')
            ->whereNull('project_feedback.id')
            ->select(
                'projects.id',
                'projects.title',
                'projects.description',
                'project_assignments.adiutor_id',
                'users.fullName as adiutor_name',
                'project_assignments.updated_at as completion_date'
            )
            ->get();
        
        return view('client.feedback', compact('user', 'givenFeedback', 'pendingFeedback'));
    }
}