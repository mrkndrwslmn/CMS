<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\GroupChat;
use App\Models\Project;

class AdiutorController extends Controller
{
    /**
     * Show adiutor dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get dashboard statistics
        $stats = [
            'active_projects' => DB::table('project_assignments')
                ->where('adiutor_id', $user->id)
                ->where('status', 'active')
                ->count(),
            'completed_projects' => DB::table('project_assignments')
                ->where('adiutor_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'pending_assignments' => DB::table('project_assignments')
                ->where('adiutor_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'total_earnings' => DB::table('project_assignments')
                ->where('adiutor_id', $user->id)
                ->where('status', 'completed')
                ->sum('agreed_rate') ?? 0,
            'total_tasks' => DB::table('tasks')
                ->join('project_assignments', 'tasks.project_id', '=', 'project_assignments.project_id')
                ->where('project_assignments.adiutor_id', $user->id)
                ->where('tasks.assignedTo', $user->id)
                ->count(),
            'completed_tasks' => DB::table('tasks')
                ->join('project_assignments', 'tasks.project_id', '=', 'project_assignments.project_id')
                ->where('project_assignments.adiutor_id', $user->id)
                ->where('tasks.assignedTo', $user->id)
                ->where('tasks.status', 'completed')
                ->count(),
            'pending_tasks' => DB::table('tasks')
                ->join('project_assignments', 'tasks.project_id', '=', 'project_assignments.project_id')
                ->where('project_assignments.adiutor_id', $user->id)
                ->where('tasks.assignedTo', $user->id)
                ->whereIn('tasks.status', ['pending', 'in_progress'])
                ->count(),
        ];

        // Get urgent tasks (due within 3 days or overdue)
        $urgentTasks = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
            ->join('users as clients', 'projects.client_id', '=', 'clients.id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->where('tasks.assignedTo', $user->id)
            ->whereNotIn('tasks.status', ['completed'])
            ->where(function($query) {
                $query->where('tasks.deadline', '<=', now()->addDays(3))
                      ->orWhere('tasks.deadline', '<=', now());
            })
            ->select(
                'tasks.*',
                'projects.title as project_title',
                'clients.fullName as client_name',
                DB::raw('CASE WHEN tasks.deadline <= NOW() THEN "overdue" ELSE "due_soon" END as urgency')
            )
            ->orderBy('tasks.deadline', 'asc')
            ->limit(5)
            ->get();

        // Get pending budget requests
        $pendingBudgetRequests = DB::table('budget_change_requests')
            ->join('tasks', 'budget_change_requests.task_id', '=', 'tasks.taskID')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('budget_change_requests.adiutor_id', $user->id)
            ->where('budget_change_requests.status', 'pending')
            ->select(
                'budget_change_requests.*',
                'tasks.taskTitle',
                'projects.title as project_title'
            )
            ->orderBy('budget_change_requests.created_at', 'desc')
            ->limit(3)
            ->get();

        // Get recent revisions
        $recentRevisions = DB::table('revision_requests')
            ->join('tasks', 'revision_requests.task_id', '=', 'tasks.taskID')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('revision_requests.assigned_adiutor_id', $user->id)
            ->whereIn('revision_requests.status', ['approved', 'pending'])
            ->select(
                'revision_requests.*',
                'tasks.taskTitle',
                'projects.title as project_title'
            )
            ->orderBy('revision_requests.created_at', 'desc')
            ->limit(3)
            ->get();

        // Calculate completion rate for progress insights
        $completionRate = $stats['total_tasks'] > 0 ? 
            round(($stats['completed_tasks'] / $stats['total_tasks']) * 100, 1) : 0;

        // Get this month's earnings
        $thisMonthEarnings = DB::table('project_assignments')
            ->where('adiutor_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('agreed_rate') ?? 0;

        // Get recent projects with enhanced details
        $recentProjects = DB::table('project_assignments')
            ->join('projects', 'project_assignments.project_id', '=', 'projects.id')
            ->join('users', 'projects.client_id', '=', 'users.id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->select(
                'projects.id as project_id',
                'projects.title',
                'projects.status as project_status',
                'projects.deadline as project_deadline',
                'project_assignments.status as assignment_status',
                'project_assignments.progress_percentage',
                'users.fullName as client_name',
                'project_assignments.created_at',
                'project_assignments.agreed_rate'
            )
            ->orderBy('project_assignments.created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent notifications (using Laravel's polymorphic notifications)
        $notifications = DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get active announcements for adiutors
        $announcements = DB::table('announcements')
            ->join('users', 'announcements.created_by', '=', 'users.id')
            ->where('announcements.status', 'active')
            ->where(function ($query) {
                $query->where('announcements.target_audience', 'LIKE', '%adiutor%')
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
                'announcements.id',
                'announcements.title',
                'announcements.content',
                'announcements.priority',
                'announcements.status',
                'announcements.starts_at',
                'announcements.expires_at',
                'announcements.created_at',
                'users.fullName as creator_name'
            )
            ->orderByRaw("FIELD(announcements.priority, 'high', 'medium', 'low')")
            ->orderBy('announcements.created_at', 'desc')
            ->get();
        
        return view('adiutor.dashboard', compact(
            'user', 
            'stats', 
            'recentProjects', 
            'notifications',
            'urgentTasks',
            'pendingBudgetRequests',
            'recentRevisions',
            'completionRate',
            'thisMonthEarnings',
            'announcements'
        ));
    }

    /**
     * Show client management
     */
    public function clients()
    {
        $user = Auth::user();
        
        // Get all clients this adiutor has worked with
        $clients = DB::table('project_assignments')
            ->join('projects', 'project_assignments.project_id', '=', 'projects.id')
            ->join('users', 'projects.client_id', '=', 'users.id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->select(
                'users.id',
                'users.fullName',
                'users.email',
                'users.phoneNumber',
                DB::raw('COUNT(projects.id) as total_projects'),
                DB::raw('SUM(CASE WHEN project_assignments.status = "completed" THEN 1 ELSE 0 END) as completed_projects'),
                DB::raw('MAX(project_assignments.created_at) as last_project_date')
            )
            ->groupBy('users.id', 'users.fullName', 'users.email', 'users.phoneNumber')
            ->orderBy('last_project_date', 'desc')
            ->get();
        
        return view('adiutor.clients.index', compact('user', 'clients'));
    }

    /**
     * Show document management
     */
    public function documents()
    {
        $user = Auth::user();
        
        // Get all documents from projects and tasks assigned to this adiutor
        $documents = DB::table('documents')
            ->leftJoin('tasks', 'documents.taskID', '=', 'tasks.taskID')
            ->leftJoin('projects', function($join) {
                $join->on('tasks.project_id', '=', 'projects.id')
                     ->orWhere('documents.project_id', '=', 'projects.id');
            })
            ->leftJoin('project_assignments', 'projects.id', '=', 'project_assignments.project_id')
            ->leftJoin('users', 'documents.uploaded_by', '=', 'users.id')
            ->where(function($query) use ($user) {
                $query->where('project_assignments.adiutor_id', $user->id)
                      ->orWhere('documents.uploaded_by', $user->id);
            })
            ->where('documents.is_archived', false)
            ->select(
                'documents.*',
                'projects.title as project_title',
                'tasks.taskTitle as task_title',
                'users.fullName as uploaded_by_name'
            )
            ->orderBy('documents.created_at', 'desc')
            ->paginate(12);
        
        // Get projects for filter dropdown
        $projects = DB::table('project_assignments')
            ->join('projects', 'project_assignments.project_id', '=', 'projects.id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->select('projects.id', 'projects.title')
            ->distinct()
            ->get();
        
        return view('adiutor.documents.index', compact('user', 'documents', 'projects'));
    }

    /**
     * Show feedback and reviews (derived from projects this adiutor worked on)
     */
    public function feedback()
    {
        $user = Auth::user();
        
        // Get feedback from projects this adiutor worked on
        $feedback = DB::table('feedbacks')
            ->join('project_assignments', 'feedbacks.project_id', '=', 'project_assignments.project_id')
            ->join('users as clients', 'feedbacks.client_id', '=', 'clients.id')
            ->join('projects', 'feedbacks.project_id', '=', 'projects.id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->select(
                'feedbacks.*',
                'clients.fullName as client_name',
                'clients.profilePic as client_photo',
                'projects.title as project_title'
            )
            ->orderBy('feedbacks.created_at', 'desc')
            ->get();

        // Get feedback statistics (derived from projects)
        $feedbackStats = [
            'total_feedback' => $feedback->count(),
            'average_rating' => $feedback->where('rating', '>', 0)->avg('rating') ?? 0,
            'five_star' => $feedback->where('rating', 5)->count(),
            'four_star' => $feedback->where('rating', 4)->count(),
            'three_star' => $feedback->where('rating', 3)->count(),
            'two_star' => $feedback->where('rating', 2)->count(),
            'one_star' => $feedback->where('rating', 1)->count(),
        ];
        
        return view('adiutor.feedback.index', compact('user', 'feedback', 'feedbackStats'));
    }

    public function groupChats()
    {
        $user = Auth::user();
        
        // Get all group chats for projects this adiutor is assigned to
        $groupChats = GroupChat::whereHas('members', function($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->with(['project', 'lastMessage', 'members'])
        ->withCount(['messages as unread_count' => function($query) use ($user) {
            $member = \DB::table('group_chat_members')
                ->where('group_chat_id', \DB::raw('group_chats.id'))
                ->where('user_id', $user->id)
                ->first();
            
            if ($member && $member->last_read_at) {
                $query->where('created_at', '>', $member->last_read_at);
            }
        }])
        ->orderBy('last_message_at', 'desc')
        ->get();

        return view('adiutor.group-chats.index', compact('user', 'groupChats'));
    }

    public function showGroupChat(Project $project)
    {
        $user = Auth::user();
        
        // Get or create group chat for this project
        $groupChat = GroupChat::getOrCreateForProject($project->id);
        
        // Check if user can access this group chat
        if (!$groupChat->canAccess($user)) {
            abort(403, 'You do not have access to this group chat.');
        }
        
        // Get all messages for this group chat
        $messages = Message::where('group_chat_id', $groupChat->id)
            ->with(['sender'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark messages as read
        $groupChat->resetUnreadForMember($user->id);
        
        return view('adiutor.group-chats.show', compact('user', 'groupChat', 'messages', 'project'));
    }
}