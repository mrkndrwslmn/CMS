<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        ];

        // Get recent projects
        $recentProjects = DB::table('project_assignments')
            ->join('projects', 'project_assignments.project_id', '=', 'projects.id')
            ->join('users', 'projects.client_id', '=', 'users.id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->select(
                'projects.title',
                'projects.status as project_status',
                'project_assignments.status as assignment_status',
                'project_assignments.progress_percentage',
                'users.fullName as client_name',
                'project_assignments.created_at'
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
        
        return view('adiutor.dashboard', compact('user', 'stats', 'recentProjects', 'notifications'));
    }

    /**
     * Show assigned tasks/projects
     */
    public function tasks()
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
                'users.email as client_email'
            )
            ->orderBy('project_assignments.created_at', 'desc')
            ->get();
        
        return view('adiutor.tasks.index', compact('user', 'projects'));
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
        
        return view('adiutor.clients', compact('user', 'clients'));
    }

    /**
     * Show document management
     */
    public function documents()
    {
        $user = Auth::user();
        
        // Get project files and documents
        $documents = DB::table('project_assignments')
            ->join('projects', 'project_assignments.project_id', '=', 'projects.id')
            ->where('project_assignments.adiutor_id', $user->id)
            ->whereNotNull('projects.attachments')
            ->select(
                'projects.id',
                'projects.title',
                'projects.attachments',
                'project_assignments.status'
            )
            ->get();
        
        return view('adiutor.documents', compact('user', 'documents'));
    }

    /**
     * Show feedback and reviews
     */
    public function feedback()
    {
        $user = Auth::user();
        
        // This would typically come from a reviews/feedback table
        // For now, we'll create a placeholder structure
        $feedback = collect(); // Placeholder for feedback data
        
        return view('adiutor.feedback', compact('user', 'feedback'));
    }
}