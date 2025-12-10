<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\ServiceRequest;
use App\Models\Document;
use App\Models\Project;
use App\Models\CustomReportTemplate;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        // Handle period filter
        $period = $request->get('period', '30');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Calculate date range
        if ($period === 'custom' && $startDate && $endDate) {
            $filterStartDate = Carbon::parse($startDate)->startOfDay();
            $filterEndDate = Carbon::parse($endDate)->endOfDay();
        } else {
            $filterStartDate = Carbon::now()->subDays((int)$period)->startOfDay();
            $filterEndDate = Carbon::now()->endOfDay();
        }
        
        // Calculate previous period for comparison
        $periodDays = $filterStartDate->diffInDays($filterEndDate);
        $previousStartDate = $filterStartDate->copy()->subDays($periodDays);
        $previousEndDate = $filterStartDate->copy()->subDay();

        // Key Performance Indicators - Current Period
        $kpis = [
            'total_users' => User::count(),
            'new_users' => User::whereBetween('created_at', [$filterStartDate, $filterEndDate])->count(),
            'previous_new_users' => User::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'new_clients' => User::where('role', 'client')->whereBetween('created_at', [$filterStartDate, $filterEndDate])->count(),
            'previous_new_clients' => User::where('role', 'client')->whereBetween('created_at', [$previousStartDate, $previousEndDate])->count(),
            'total_tasks' => Task::count(),
            'active_tasks' => Task::whereIn('status', ['pending', 'in_progress'])->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'tasks_this_period' => Task::whereBetween('created_at', [$filterStartDate, $filterEndDate])->count(),
            'total_documents' => Document::count(),
            'documents_this_period' => Document::whereBetween('created_at', [$filterStartDate, $filterEndDate])->count(),
            'total_document_size' => Document::sum('fileSize') ?? 0,
            'total_projects' => Project::count(),
            'active_projects' => Project::whereIn('status', ['in_progress', 'pending'])->count(),
            'total_requests' => ServiceRequest::count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
        ];

        // Calculate completion rate
        $kpis['completion_rate'] = $kpis['total_tasks'] > 0 
            ? round(($kpis['completed_tasks'] / $kpis['total_tasks']) * 100, 1) 
            : 0;

        // User Activity Trend (daily registrations for the period)
        $userActivityTrend = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$filterStartDate, $filterEndDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill in missing dates with zero counts
        $userActivity = [];
        $currentDate = $filterStartDate->copy();
        while ($currentDate <= $filterEndDate) {
            $dateKey = $currentDate->format('Y-m-d');
            $userActivity[] = [
                'date' => $currentDate->format('M d'),
                'count' => $userActivityTrend->get($dateKey)?->count ?? 0
            ];
            $currentDate->addDay();
        }

        // Task Status Distribution
        $taskStatusDistribution = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Recent Activities (mix of recent actions)
        $recentActivities = collect();
        
        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($u) => [
                'type' => 'user_registered',
                'icon' => 'user-plus',
                'color' => 'primary',
                'message' => "New {$u->role} registered: {$u->fullName}",
                'time' => $u->created_at,
            ]);
        $recentActivities = $recentActivities->merge($recentUsers);
        
        // Recent tasks
        $recentTasks = Task::with('project')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'type' => 'task_created',
                'icon' => 'list-todo',
                'color' => 'warning',
                'message' => "Task created: {$t->taskTitle}",
                'time' => $t->created_at,
            ]);
        $recentActivities = $recentActivities->merge($recentTasks);
        
        // Recent completed tasks
        $completedTasks = Task::where('status', 'completed')
            ->whereNotNull('completedAt')
            ->orderBy('completedAt', 'desc')
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'type' => 'task_completed',
                'icon' => 'check-circle',
                'color' => 'success',
                'message' => "Task completed: {$t->taskTitle}",
                'time' => $t->completedAt,
            ]);
        $recentActivities = $recentActivities->merge($completedTasks);
        
        // Sort by time and take latest 10
        $recentActivities = $recentActivities->sortByDesc('time')->take(10)->values();

        // Top Performers (Adiutors with most completed tasks)
        $topPerformers = User::where('role', 'adiutor')
            ->where('status', 'active')
            ->withCount([
                'assignedTasks as completed_tasks_count' => function($query) {
                    $query->where('status', 'completed');
                },
                'assignedTasks as total_tasks_count'
            ])
            ->having('completed_tasks_count', '>', 0)
            ->orderBy('completed_tasks_count', 'desc')
            ->take(5)
            ->get();

        // Detailed Report Metrics (current vs previous period comparison)
        $reportMetrics = [
            [
                'name' => 'New Users',
                'current' => $kpis['new_users'],
                'previous' => $kpis['previous_new_users'],
            ],
            [
                'name' => 'New Clients',
                'current' => $kpis['new_clients'],
                'previous' => $kpis['previous_new_clients'],
            ],
            [
                'name' => 'Tasks Created',
                'current' => Task::whereBetween('created_at', [$filterStartDate, $filterEndDate])->count(),
                'previous' => Task::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count(),
            ],
            [
                'name' => 'Tasks Completed',
                'current' => Task::where('status', 'completed')
                    ->whereBetween('completedAt', [$filterStartDate, $filterEndDate])->count(),
                'previous' => Task::where('status', 'completed')
                    ->whereBetween('completedAt', [$previousStartDate, $previousEndDate])->count(),
            ],
            [
                'name' => 'Service Requests',
                'current' => ServiceRequest::whereBetween('created_at', [$filterStartDate, $filterEndDate])->count(),
                'previous' => ServiceRequest::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count(),
            ],
            [
                'name' => 'Documents Uploaded',
                'current' => $kpis['documents_this_period'],
                'previous' => Document::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count(),
            ],
        ];

        // Calculate change percentages
        foreach ($reportMetrics as &$metric) {
            if ($metric['previous'] > 0) {
                $metric['change'] = round((($metric['current'] - $metric['previous']) / $metric['previous']) * 100, 1);
            } else {
                $metric['change'] = $metric['current'] > 0 ? 100 : 0;
            }
        }

        return view('admin.reports.index', compact(
            'kpis', 
            'userActivity', 
            'taskStatusDistribution', 
            'recentActivities', 
            'topPerformers', 
            'reportMetrics',
            'period',
            'filterStartDate',
            'filterEndDate'
        ));
    }
    
    public function dashboard(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);
        
        // Key Performance Indicators
        $kpis = [
            'total_users' => User::count(),
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
            'active_clients' => User::where('role', 'client')->where('status', 'active')->count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'total_requests' => ServiceRequest::count(),
            'approved_requests' => ServiceRequest::where('status', 'approved')->count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'total_documents' => Document::count(),
            'document_size' => Document::sum('fileSize'),
        ];
        
        // User Growth Data (last 12 months)
        $userGrowth = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        // Task Completion Rate (last 30 days)
        $taskCompletion = Task::selectRaw('
            DATE(created_at) as date,
            COUNT(*) as total,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed
        ')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Request Status Distribution
        $requestStatus = ServiceRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
            
        // Top Clients by Task Count
        $topClients = User::where('role', 'client')
            ->withCount('tasks')
            ->orderBy('tasks_count', 'desc')
            ->take(10)
            ->get();
            
        // Adiutor Performance
        $adiutorPerformance = User::where('role', 'adiutor')
            ->withCount([
                'assignedTasks',
                'assignedTasks as completed_tasks_count' => function($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->get();
            
        return view('admin.reports.dashboard', compact(
            'kpis', 'userGrowth', 'taskCompletion', 'requestStatus', 
            'topClients', 'adiutorPerformance', 'period'
        ));
    }
    
    public function users(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        // User Statistics
        $stats = [
            'total_users' => User::count(),
            'clients' => User::where('role', 'client')->count(),
            'adiutors' => User::where('role', 'adiutor')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'active_users' => User::where('status', 'active')->count(),
            'new_registrations' => User::where('created_at', '>=', $startDate)->count(),
        ];
        
        // User Registration Trend
        $registrationTrend = User::selectRaw('DATE(created_at) as date, role, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date', 'role')
            ->orderBy('date')
            ->get();
            
        // User Status Distribution
        $statusDistribution = User::selectRaw('role, status, COUNT(*) as count')
            ->groupBy('role', 'status')
            ->get();
            
        // Most Active Users (by task participation)
        $activeUsers = User::select('users.*')
            ->leftJoin('tasks', function($join) {
                $join->on('users.id', '=', 'tasks.client_id')
                     ->orOn('users.id', '=', 'tasks.assignedTo');
            })
            ->selectRaw('users.*, COUNT(tasks.taskID) as task_count')
            ->groupBy('users.id')
            ->orderBy('task_count', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.reports.users', compact(
            'stats', 'registrationTrend', 'statusDistribution', 'activeUsers', 'period'
        ));
    }
    
    public function tasks(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        // Task Statistics
        $stats = [
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'overdue_tasks' => Task::where('deadline', '<', now())->where('status', '!=', 'completed')->count(),
            'average_completion_time' => Task::where('status', 'completed')
                ->whereNotNull('completedAt')
                ->selectRaw('AVG(DATEDIFF(completedAt, created_at)) as avg_days')
                ->value('avg_days'),
        ];
        
        // Task Creation Trend
        $taskTrend = Task::selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
            
        // Priority Distribution
        $priorityDistribution = Task::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->get();
            
        // Tasks by Adiutor
        $tasksByAdiutor = User::where('role', 'adiutor')
            ->withCount([
                'assignedTasks',
                'assignedTasks as completed_count' => function($query) {
                    $query->where('status', 'completed');
                },
                'assignedTasks as overdue_count' => function($query) {
                    $query->where('deadline', '<', now())->where('status', '!=', 'completed');
                }
            ])
            ->get();
            
        // Average Task Duration by Priority
        $durationByPriority = Task::where('status', 'completed')
            ->whereNotNull('completedAt')
            ->selectRaw('priority, AVG(DATEDIFF(completedAt, created_at)) as avg_days')
            ->groupBy('priority')
            ->get();
            
        return view('admin.reports.tasks', compact(
            'stats', 'taskTrend', 'priorityDistribution', 'tasksByAdiutor', 'durationByPriority', 'period'
        ));
    }
    
    public function requests(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        // Request Statistics
        $stats = [
            'total_requests' => ServiceRequest::count(),
            'approved_requests' => ServiceRequest::where('status', 'approved')->count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'rejected_requests' => ServiceRequest::where('status', 'rejected')->count(),
            'approval_rate' => ServiceRequest::where('status', 'approved')->count() / max(ServiceRequest::count(), 1) * 100,
            'average_response_time' => ServiceRequest::whereNotNull('reviewed_at')
                ->selectRaw('AVG(DATEDIFF(reviewed_at, created_at)) as avg_days')
                ->value('avg_days'),
        ];
        
        // Request Trend
        $requestTrend = ServiceRequest::selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
            
        // Service Type Distribution with status breakdown
        $typeDistribution = ServiceRequest::selectRaw('service_type, COUNT(*) as count, MAX(created_at) as latest_request')
            ->groupBy('service_type')
            ->get()
            ->map(function($type) {
                // Get status breakdown for this service type
                $statusCounts = ServiceRequest::where('service_type', $type->service_type)
                    ->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray();
                $type->status_breakdown = $statusCounts;
                return $type;
            });
            
        // Priority Distribution
        $priorityDistribution = ServiceRequest::selectRaw('priority, COUNT(*) as count')
            ->whereNotNull('priority')
            ->groupBy('priority')
            ->get();
            
        // Top Requesting Clients
        $topClients = User::where('role', 'client')
            ->withCount('serviceRequests')
            ->orderBy('service_requests_count', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.reports.requests', compact(
            'stats', 'requestTrend', 'typeDistribution', 'priorityDistribution', 'topClients', 'period'
        ));
    }
    
    public function documents(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        // Document Statistics
        $stats = [
            'total_documents' => Document::count(),
            'total_size' => Document::sum('fileSize'),
            'uploaded_this_period' => Document::where('created_at', '>=', $startDate)->count(),
            'size_this_period' => Document::where('created_at', '>=', $startDate)->sum('fileSize'),
            'average_size' => Document::avg('fileSize'),
        ];
        
        // Upload Trend
        $uploadTrend = Document::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(fileSize) as total_size')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Type Distribution
        $typeDistribution = Document::selectRaw('fileType as type, COUNT(*) as count, SUM(fileSize) as total_size')
            ->groupBy('fileType')
            ->get();
            
        // Category Distribution
        $categoryDistribution = Document::selectRaw('document_type as category, COUNT(*) as count')
            ->groupBy('document_type')
            ->get();
            
        // Top Uploaders
        $topUploaders = User::withCount('uploadedDocuments')
            ->orderBy('uploaded_documents_count', 'desc')
            ->take(10)
            ->get();
            
        // Storage Usage by Client
        $storageByClient = User::where('role', 'client')
            ->leftJoin('documents', 'users.id', '=', 'documents.client_id')
            ->selectRaw('users.fullName, users.id, COALESCE(SUM(documents.fileSize), 0) as total_size, COUNT(documents.documentID) as document_count')
            ->groupBy('users.id', 'users.fullName')
            ->orderBy('total_size', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.reports.documents', compact(
            'stats', 'uploadTrend', 'typeDistribution', 'categoryDistribution', 'topUploaders', 'storageByClient', 'period'
        ));
    }
    
    public function projects(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        // Project Statistics
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'in_progress')->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'pending_projects' => Project::where('status', 'pending')->count(),
            'completion_rate' => Project::where('status', 'completed')->count() / max(Project::count(), 1) * 100,
            'total_budget' => Project::sum('budget'),
            'average_budget' => Project::avg('budget'),
            'overdue_projects' => Project::where('deadline', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
        ];
        
        // Project Status Trend
        $projectTrend = Project::selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
            
        // Budget Distribution
        $budgetRanges = Project::selectRaw('
            CASE 
                WHEN budget < 1000 THEN "Under ₱1K"
                WHEN budget < 5000 THEN "₱1K - ₱5K"
                WHEN budget < 10000 THEN "₱5K - ₱10K"
                WHEN budget < 25000 THEN "₱10K - ₱25K"
                ELSE "Over ₱25K"
            END as budget_range,
            COUNT(*) as count,
            SUM(budget) as total_value
        ')
        ->whereNotNull('budget')
        ->groupBy('budget_range')
        ->orderByRaw('MIN(budget)')
        ->get();
        
        // Projects by Priority
        $priorityDistribution = Project::selectRaw('priority, COUNT(*) as count, AVG(budget) as avg_budget')
            ->whereNotNull('priority')
            ->groupBy('priority')
            ->get();
            
        // Top Clients by Project Count
        $topClients = User::where('role', 'client')
            ->withCount('projects')
            ->orderBy('projects_count', 'desc')
            ->take(10)
            ->get();
            
        // Project Duration Analysis
        $durationAnalysis = Project::where('status', 'completed')
            ->whereNotNull('completed_at')
            ->whereNotNull('started_at')
            ->selectRaw('AVG(DATEDIFF(completed_at, started_at)) as avg_days, priority')
            ->groupBy('priority')
            ->get();
            
        return view('admin.reports.projects', compact(
            'stats', 'projectTrend', 'budgetRanges', 'priorityDistribution', 
            'topClients', 'durationAnalysis', 'period'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'users');
        $format = $request->get('format', 'csv');
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        $filename = $type . '_report_' . now()->format('Y-m-d_H-i-s') . '.' . $format;
        
        switch ($type) {
            case 'dashboard':
                // Dashboard export - combines summary data from users, tasks, requests, and projects
                $users = User::where('created_at', '>=', $startDate)->get();
                $tasks = Task::with(['client', 'assignedUser'])->where('created_at', '>=', $startDate)->get();
                $requests = ServiceRequest::with(['client'])->where('created_at', '>=', $startDate)->get();
                $projects = Project::with(['client'])->where('created_at', '>=', $startDate)->get();
                
                $headers = ['Category', 'Metric', 'Value'];
                $callback = function() use ($users, $tasks, $requests, $projects, $headers, $startDate) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $headers);
                    
                    // User metrics
                    fputcsv($file, ['Users', 'Total Users', User::count()]);
                    fputcsv($file, ['Users', 'New Users (Period)', $users->count()]);
                    fputcsv($file, ['Users', 'Total Clients', User::where('role', 'client')->count()]);
                    fputcsv($file, ['Users', 'New Clients (Period)', $users->where('role', 'client')->count()]);
                    
                    // Task metrics
                    fputcsv($file, ['Tasks', 'Total Tasks', Task::count()]);
                    fputcsv($file, ['Tasks', 'Tasks Created (Period)', $tasks->count()]);
                    fputcsv($file, ['Tasks', 'Pending Tasks', Task::where('status', 'pending')->count()]);
                    fputcsv($file, ['Tasks', 'In Progress Tasks', Task::where('status', 'in_progress')->count()]);
                    fputcsv($file, ['Tasks', 'Completed Tasks', Task::where('status', 'completed')->count()]);
                    
                    // Request metrics
                    fputcsv($file, ['Requests', 'Total Requests', ServiceRequest::count()]);
                    fputcsv($file, ['Requests', 'Requests Created (Period)', $requests->count()]);
                    fputcsv($file, ['Requests', 'Pending Requests', ServiceRequest::where('status', 'pending')->count()]);
                    fputcsv($file, ['Requests', 'Approved Requests', ServiceRequest::where('status', 'approved')->count()]);
                    
                    // Project metrics
                    fputcsv($file, ['Projects', 'Total Projects', Project::count()]);
                    fputcsv($file, ['Projects', 'Projects Created (Period)', $projects->count()]);
                    fputcsv($file, ['Projects', 'Active Projects', Project::whereIn('status', ['in_progress', 'pending'])->count()]);
                    fputcsv($file, ['Projects', 'Completed Projects', Project::where('status', 'completed')->count()]);
                    
                    fclose($file);
                };
                break;
            
            case 'users':
                $data = User::where('created_at', '>=', $startDate)->get();
                $headers = ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At'];
                $callback = function() use ($data, $headers) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $headers);
                    foreach ($data as $user) {
                        fputcsv($file, [
                            $user->id,
                            $user->fullName,
                            $user->email,
                            $user->role,
                            $user->status,
                            $user->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                    fclose($file);
                };
                break;
                
            case 'tasks':
                $data = Task::with(['client', 'assignedUser'])->where('created_at', '>=', $startDate)->get();
                $headers = ['ID', 'Title', 'Client', 'Assignee', 'Status', 'Priority', 'Created At', 'Deadline'];
                $callback = function() use ($data, $headers) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $headers);
                    foreach ($data as $task) {
                        fputcsv($file, [
                            $task->taskID,
                            $task->taskTitle,
                            $task->client->fullName ?? 'N/A',
                            $task->assignedUser->fullName ?? 'Unassigned',
                            $task->status,
                            $task->priority,
                            $task->created_at->format('Y-m-d H:i:s'),
                            $task->deadline ? $task->deadline->format('Y-m-d H:i:s') : 'N/A',
                        ]);
                    }
                    fclose($file);
                };
                break;
                
            case 'requests':
                $data = ServiceRequest::with(['client'])->where('created_at', '>=', $startDate)->get();
                $headers = ['ID', 'Title', 'Client', 'Service Type', 'Status', 'Priority', 'Budget', 'Created At'];
                $callback = function() use ($data, $headers) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $headers);
                    foreach ($data as $request) {
                        fputcsv($file, [
                            $request->id,
                            $request->project_name,
                            $request->client->fullName ?? 'N/A',
                            $request->service_type,
                            $request->status,
                            $request->priority ?? 'N/A',
                            $request->approved_budget ? 'PHP ' . number_format($request->approved_budget, 2) : 'N/A',
                            $request->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                    fclose($file);
                };
                break;
                
            case 'projects':
                $data = Project::with(['client', 'serviceRequest'])->where('created_at', '>=', $startDate)->get();
                $headers = ['ID', 'Title', 'Client', 'Status', 'Priority', 'Budget', 'Started At', 'Deadline'];
                $callback = function() use ($data, $headers) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $headers);
                    foreach ($data as $project) {
                        fputcsv($file, [
                            $project->id,
                            $project->title,
                            $project->client->fullName ?? 'N/A',
                            $project->status,
                            $project->priority ?? 'N/A',
                            $project->budget ? 'PHP ' . number_format($project->budget, 2) : 'N/A',
                            $project->started_at ? $project->started_at->format('Y-m-d H:i:s') : 'N/A',
                            $project->deadline ? $project->deadline->format('Y-m-d H:i:s') : 'N/A',
                        ]);
                    }
                    fclose($file);
                };
                break;
                
            case 'documents':
                $data = Document::with(['client'])->where('created_at', '>=', $startDate)->get();
                $headers = ['ID', 'Title', 'Client', 'File Type', 'File Size', 'Document Type', 'Created At'];
                $callback = function() use ($data, $headers) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $headers);
                    foreach ($data as $document) {
                        fputcsv($file, [
                            $document->documentID,
                            $document->documentTitle,
                            $document->client->fullName ?? 'N/A',
                            $document->fileType,
                            number_format($document->fileSize / 1024, 2) . ' KB',
                            $document->document_type ?? 'N/A',
                            $document->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                    fclose($file);
                };
                break;
                
            case 'custom':
                // Handle custom report export
                $reportType = $request->get('report_type');
                $dateRange = $request->get('date_range', '30');
                $startDate = $request->get('start_date');
                $endDate = $request->get('end_date');
                
                if ($dateRange === 'custom' && $startDate && $endDate) {
                    $start = Carbon::parse($startDate);
                    $end = Carbon::parse($endDate);
                } else {
                    $days = intval($dateRange);
                    $start = Carbon::now()->subDays($days);
                    $end = Carbon::now();
                }
                
                $customData = $this->generateReportData($reportType, $start, $end, $request->all());
                $data = $customData['table'] ?? collect();
                $headers = $this->getCustomReportHeaders($reportType);
                
                $callback = function() use ($data, $headers, $reportType) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $headers);
                    foreach ($data as $item) {
                        $row = $this->formatCustomReportRow($item, $reportType);
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };
                break;
                
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }
        
        $responseHeaders = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->stream($callback, 200, $responseHeaders);
    }
    
    public function customReport(Request $request)
    {
        // This would allow admins to create custom reports with filters
        return view('admin.reports.custom');
    }
    
    /**
     * Generate custom report data based on filters
     */
    public function generateCustomReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:users,tasks,requests,documents,financial,performance',
            'date_range' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'charts' => 'array',
            'include_table' => 'boolean',
            'include_summary' => 'boolean',
            'include_export' => 'boolean'
        ]);

        $reportType = $request->input('report_type');
        $dateRange = $request->input('date_range');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Calculate date range
        if ($dateRange === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
        } else {
            $days = intval($dateRange);
            $start = Carbon::now()->subDays($days);
            $end = Carbon::now();
        }

        $data = $this->generateReportData($reportType, $start, $end, $request->all());

        return response()->json([
            'success' => true,
            'data' => $data,
            'config' => [
                'charts' => $request->input('charts', []),
                'include_table' => $request->boolean('include_table'),
                'include_summary' => $request->boolean('include_summary'),
                'include_export' => $request->boolean('include_export')
            ]
        ]);
    }

    /**
     * Preview custom report data
     */
    public function previewCustomReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:users,tasks,requests,documents,financial,performance',
            'date_range' => 'required|string'
        ]);

        $reportType = $request->input('report_type');
        $dateRange = $request->input('date_range');
        
        $days = $dateRange === 'custom' ? 30 : intval($dateRange);
        $start = Carbon::now()->subDays($days);
        $end = Carbon::now();

        $previewData = $this->generatePreviewData($reportType, $start, $end, $request->all());

        return response()->json([
            'success' => true,
            'preview' => $previewData
        ]);
    }

    /**
     * Save custom report template
     */
    public function saveCustomTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'config' => 'required|array'
        ]);

        $template = CustomReportTemplate::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'type' => $request->input('config.report_type', 'custom'),
            'config' => $request->input('config'),
            'created_by' => auth()->id(),
            'is_public' => $request->boolean('is_public', false)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template saved successfully',
            'template' => $template
        ]);
    }

    /**
     * Get saved custom templates
     */
    public function getCustomTemplates(Request $request)
    {
        $templates = CustomReportTemplate::accessibleBy(auth()->id())
            ->with('creator:id,fullName')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'templates' => $templates
        ]);
    }

    /**
     * Get filter options for specific report type
     */
    public function getFilterOptions(Request $request, $type)
    {
        $options = [];

        switch ($type) {
            case 'users':
                $options = [
                    'roles' => User::distinct()->pluck('role')->filter()->values(),
                    'statuses' => User::distinct()->pluck('status')->filter()->values()
                ];
                break;
            case 'tasks':
                $options = [
                    'statuses' => Task::distinct()->pluck('status')->filter()->values(),
                    'priorities' => Task::distinct()->pluck('priority')->filter()->values(),
                    'assignees' => User::where('role', 'adiutor')->select('id', 'fullName')->get()
                ];
                break;
            case 'requests':
                $options = [
                    'statuses' => ServiceRequest::distinct()->pluck('status')->filter()->values(),
                    'service_types' => ServiceRequest::distinct()->pluck('service_type')->filter()->values(),
                    'priorities' => ServiceRequest::distinct()->pluck('priority')->filter()->values()
                ];
                break;
            case 'documents':
                $options = [
                    'file_types' => Document::distinct()->pluck('fileType')->filter()->values(),
                    'categories' => Document::distinct()->pluck('document_type')->filter()->values()
                ];
                break;
        }

        return response()->json([
            'success' => true,
            'options' => $options
        ]);
    }

    /**
     * Generate report data based on type and filters
     */
    private function generateReportData($type, $start, $end, $filters)
    {
        switch ($type) {
            case 'users':
                return $this->generateUserReportData($start, $end, $filters);
            case 'tasks':
                return $this->generateTaskReportData($start, $end, $filters);
            case 'requests':
                return $this->generateRequestReportData($start, $end, $filters);
            case 'documents':
                return $this->generateDocumentReportData($start, $end, $filters);
            case 'financial':
                return $this->generateFinancialReportData($start, $end, $filters);
            case 'performance':
                return $this->generatePerformanceReportData($start, $end, $filters);
            default:
                return [];
        }
    }

    /**
     * Generate preview data for reports
     */
    private function generatePreviewData($type, $start, $end, $filters)
    {
        $baseQuery = $this->getBaseQuery($type)->whereBetween('created_at', [$start, $end]);
        
        $total = $baseQuery->count();
        $recent = $baseQuery->where('created_at', '>=', $start->copy()->addDays(-7))->count();
        $growthRate = $total > 0 ? (($recent / $total) * 100) : 0;
        
        return [
            'total_records' => $total,
            'recent_count' => $recent,
            'growth_rate' => round($growthRate, 1),
            'performance_score' => rand(75, 95),
            'status' => $total > 0 ? 'success' : 'warning'
        ];
    }

    /**
     * Get base query for different report types
     */
    private function getBaseQuery($type)
    {
        switch ($type) {
            case 'users':
                return User::query();
            case 'tasks':
                return Task::query();
            case 'requests':
                return ServiceRequest::query();
            case 'documents':
                return Document::query();
            default:
                return collect();
        }
    }

    /**
     * Generate user report data
     */
    private function generateUserReportData($start, $end, $filters)
    {
        $query = User::whereBetween('created_at', [$start, $end]);
        
        if (!empty($filters['user_role'])) {
            $query->where('role', $filters['user_role']);
        }
        if (!empty($filters['user_status'])) {
            $query->where('status', $filters['user_status']);
        }

        $summary = [
            'total_users' => $query->count(),
            'new_users' => $query->where('created_at', '>=', $start)->count(),
            'active_users' => $query->where('status', 'active')->count(),
            'growth_rate' => $this->calculateGrowthRate(User::class, $start, $end)
        ];

        $chartData = $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $tableData = $query->select('id', 'fullName', 'email', 'role', 'status', 'created_at')
            ->latest()
            ->take(50)
            ->get();

        return [
            'summary' => $summary,
            'charts' => [
                'trend' => $chartData,
                'role_distribution' => User::selectRaw('role, COUNT(*) as count')->groupBy('role')->get()
            ],
            'table' => $tableData
        ];
    }

    /**
     * Generate task report data
     */
    private function generateTaskReportData($start, $end, $filters)
    {
        $query = Task::whereBetween('created_at', [$start, $end]);
        
        if (!empty($filters['task_status'])) {
            $query->where('status', $filters['task_status']);
        }
        if (!empty($filters['task_priority'])) {
            $query->where('priority', $filters['task_priority']);
        }
        if (!empty($filters['assignee'])) {
            $query->where('assignedTo', $filters['assignee']);
        }

        $summary = [
            'total_tasks' => $query->count(),
            'completed_tasks' => $query->where('status', 'completed')->count(),
            'overdue_tasks' => $query->where('deadline', '<', now())->whereNotIn('status', ['completed'])->count(),
            'completion_rate' => $this->calculateCompletionRate($query)
        ];

        $chartData = $query->selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        $tableData = $query->with(['assignee:id,fullName', 'client:id,fullName'])
            ->select('taskID', 'taskName', 'assignedTo', 'client_id', 'status', 'priority', 'deadline', 'created_at')
            ->latest()
            ->take(50)
            ->get();

        return [
            'summary' => $summary,
            'charts' => [
                'trend' => $chartData,
                'priority_distribution' => $query->selectRaw('priority, COUNT(*) as count')->groupBy('priority')->get(),
                'status_distribution' => $query->selectRaw('status, COUNT(*) as count')->groupBy('status')->get()
            ],
            'table' => $tableData
        ];
    }

    /**
     * Generate request report data
     */
    private function generateRequestReportData($start, $end, $filters)
    {
        $query = ServiceRequest::whereBetween('created_at', [$start, $end]);
        
        if (!empty($filters['request_status'])) {
            $query->where('status', $filters['request_status']);
        }
        if (!empty($filters['service_type'])) {
            $query->where('service_type', $filters['service_type']);
        }

        $summary = [
            'total_requests' => $query->count(),
            'approved_requests' => $query->where('status', 'approved')->count(),
            'pending_requests' => $query->where('status', 'pending')->count(),
            'approval_rate' => $this->calculateApprovalRate($query)
        ];

        $chartData = $query->selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        $tableData = $query->with(['client:id,fullName'])
            ->select('id', 'project_name', 'service_type', 'status', 'approved_budget', 'created_at')
            ->latest()
            ->take(50)
            ->get();

        return [
            'summary' => $summary,
            'charts' => [
                'trend' => $chartData,
                'service_type_distribution' => $query->selectRaw('service_type, COUNT(*) as count')->groupBy('service_type')->get()
            ],
            'table' => $tableData
        ];
    }

    /**
     * Generate document report data
     */
    private function generateDocumentReportData($start, $end, $filters)
    {
        $query = Document::whereBetween('created_at', [$start, $end]);
        
        if (!empty($filters['file_type'])) {
            $query->where('fileType', $filters['file_type']);
        }
        if (!empty($filters['category'])) {
            $query->where('document_type', $filters['category']);
        }

        $summary = [
            'total_documents' => $query->count(),
            'total_size' => $query->sum('fileSize'),
            'avg_size' => $query->avg('fileSize'),
            'upload_trend' => $this->calculateGrowthRate(Document::class, $start, $end)
        ];

        $chartData = $query->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(fileSize) as total_size')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $tableData = $query->with(['client:id,fullName'])
            ->select('documentID', 'documentTitle', 'fileType', 'fileSize', 'document_type', 'created_at')
            ->latest()
            ->take(50)
            ->get();

        return [
            'summary' => $summary,
            'charts' => [
                'trend' => $chartData,
                'type_distribution' => $query->selectRaw('fileType, COUNT(*) as count')->groupBy('fileType')->get(),
                'category_distribution' => $query->selectRaw('document_type, COUNT(*) as count')->groupBy('document_type')->get()
            ],
            'table' => $tableData
        ];
    }

    /**
     * Generate financial report data
     */
    private function generateFinancialReportData($start, $end, $filters)
    {
        $requests = ServiceRequest::whereBetween('created_at', [$start, $end]);
        
        $summary = [
            'total_revenue' => $requests->sum('approved_budget'),
            'pending_revenue' => $requests->where('status', 'pending_payment')->sum('approved_budget'),
            'completed_projects' => $requests->where('status', 'completed')->count(),
            'avg_project_value' => $requests->avg('approved_budget')
        ];

        $revenueData = $requests->selectRaw('DATE(created_at) as date, SUM(approved_budget) as revenue')
            ->whereNotNull('approved_budget')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'summary' => $summary,
            'charts' => [
                'revenue_trend' => $revenueData,
                'payment_status' => $requests->selectRaw('status, SUM(approved_budget) as total')->groupBy('status')->get()
            ],
            'table' => $requests->with(['client:id,fullName'])
                ->select('id', 'project_name', 'approved_budget', 'status', 'created_at')
                ->whereNotNull('approved_budget')
                ->latest()
                ->take(50)
                ->get()
        ];
    }

    /**
     * Generate performance report data
     */
    private function generatePerformanceReportData($start, $end, $filters)
    {
        $summary = [
            'total_users' => User::count(),
            'active_projects' => Project::where('status', 'in_progress')->count(),
            'completed_tasks' => Task::where('status', 'completed')->whereBetween('completedAt', [$start, $end])->count(),
            'system_uptime' => 99.9 // Simulated
        ];

        return [
            'summary' => $summary,
            'charts' => [
                'performance_metrics' => [
                    ['metric' => 'Response Time', 'value' => rand(200, 500), 'unit' => 'ms'],
                    ['metric' => 'Task Completion Rate', 'value' => rand(85, 95), 'unit' => '%'],
                    ['metric' => 'User Satisfaction', 'value' => rand(4.2, 4.8), 'unit' => '/5'],
                ]
            ],
            'table' => collect([]) // No specific table for performance metrics
        ];
    }

    /**
     * Calculate growth rate between periods
     */
    private function calculateGrowthRate($model, $start, $end)
    {
        $previousPeriod = $start->copy()->subDays($end->diffInDays($start));
        $currentCount = $model::whereBetween('created_at', [$start, $end])->count();
        $previousCount = $model::whereBetween('created_at', [$previousPeriod, $start])->count();
        
        if ($previousCount == 0) return $currentCount > 0 ? 100 : 0;
        
        return round((($currentCount - $previousCount) / $previousCount) * 100, 1);
    }

    /**
     * Calculate completion rate for tasks
     */
    private function calculateCompletionRate($query)
    {
        $total = $query->count();
        if ($total == 0) return 0;
        
        $completed = $query->where('status', 'completed')->count();
        return round(($completed / $total) * 100, 1);
    }

    /**
     * Calculate approval rate for requests
     */
    private function calculateApprovalRate($query)
    {
        $total = $query->count();
        if ($total == 0) return 0;
        
        $approved = $query->where('status', 'approved')->count();
        return round(($approved / $total) * 100, 1);
    }
    
    /**
     * Get headers for custom report export
     */
    private function getCustomReportHeaders($reportType)
    {
        switch ($reportType) {
            case 'users':
                return ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At'];
            case 'tasks':
                return ['ID', 'Task Name', 'Assignee', 'Client', 'Status', 'Priority', 'Deadline', 'Created At'];
            case 'requests':
                return ['ID', 'Project Name', 'Client', 'Service Type', 'Status', 'Budget', 'Created At'];
            case 'documents':
                return ['ID', 'Title', 'Type', 'Size', 'Category', 'Client', 'Created At'];
            case 'financial':
                return ['ID', 'Project', 'Client', 'Budget', 'Status', 'Created At'];
            case 'performance':
                return ['Metric', 'Value', 'Unit', 'Date'];
            default:
                return ['Data'];
        }
    }
    
    /**
     * Format row data for custom report export
     */
    private function formatCustomReportRow($item, $reportType)
    {
        switch ($reportType) {
            case 'users':
                return [
                    $item->id,
                    $item->fullName,
                    $item->email,
                    $item->role,
                    $item->status,
                    $item->created_at->format('Y-m-d H:i:s')
                ];
            case 'tasks':
                return [
                    $item->taskID,
                    $item->taskName,
                    $item->assignee->fullName ?? 'Unassigned',
                    $item->client->fullName ?? 'N/A',
                    $item->status,
                    $item->priority,
                    $item->deadline ? $item->deadline->format('Y-m-d') : 'N/A',
                    $item->created_at->format('Y-m-d H:i:s')
                ];
            case 'requests':
                return [
                    $item->id,
                    $item->project_name,
                    $item->client->fullName ?? 'N/A',
                    $item->service_type,
                    $item->status,
                    $item->approved_budget ? '$' . number_format($item->approved_budget, 2) : 'N/A',
                    $item->created_at->format('Y-m-d H:i:s')
                ];
            case 'documents':
                return [
                    $item->documentID,
                    $item->documentTitle,
                    $item->fileType,
                    $item->fileSize ? number_format($item->fileSize / 1024, 2) . ' KB' : 'N/A',
                    $item->document_type,
                    $item->client->fullName ?? 'N/A',
                    $item->created_at->format('Y-m-d H:i:s')
                ];
            case 'financial':
                return [
                    $item->id,
                    $item->project_name,
                    $item->client->fullName ?? 'N/A',
                    $item->approved_budget ? '$' . number_format($item->approved_budget, 2) : 'N/A',
                    $item->status,
                    $item->created_at->format('Y-m-d H:i:s')
                ];
            default:
                return [$item];
        }
    }
}