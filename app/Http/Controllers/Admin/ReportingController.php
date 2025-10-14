<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\Form;
use App\Models\Document;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportingController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
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
            'total_requests' => Form::count(),
            'approved_requests' => Form::where('status', 'approved')->count(),
            'pending_requests' => Form::where('status', 'pending')->count(),
            'total_documents' => Document::count(),
            'document_size' => Document::sum('file_size'),
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
        $requestStatus = Form::selectRaw('status, COUNT(*) as count')
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
                     ->orOn('users.id', '=', 'tasks.adiutor_id');
            })
            ->selectRaw('users.*, COUNT(tasks.id) as task_count')
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
            'overdue_tasks' => Task::where('due_date', '<', now())->where('status', '!=', 'completed')->count(),
            'average_completion_time' => Task::where('status', 'completed')
                ->whereNotNull('completed_at')
                ->selectRaw('AVG(DATEDIFF(completed_at, created_at)) as avg_days')
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
                    $query->where('due_date', '<', now())->where('status', '!=', 'completed');
                }
            ])
            ->get();
            
        // Average Task Duration by Priority
        $durationByPriority = Task::where('status', 'completed')
            ->whereNotNull('completed_at')
            ->selectRaw('priority, AVG(DATEDIFF(completed_at, created_at)) as avg_days')
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
            'total_requests' => Form::count(),
            'approved_requests' => Form::where('status', 'approved')->count(),
            'pending_requests' => Form::where('status', 'pending')->count(),
            'rejected_requests' => Form::where('status', 'rejected')->count(),
            'approval_rate' => Form::where('status', 'approved')->count() / max(Form::count(), 1) * 100,
            'average_response_time' => Form::whereNotNull('reviewed_at')
                ->selectRaw('AVG(DATEDIFF(reviewed_at, created_at)) as avg_days')
                ->value('avg_days'),
        ];
        
        // Request Trend
        $requestTrend = Form::selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
            
        // Type Distribution
        $typeDistribution = Form::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();
            
        // Priority Distribution
        $priorityDistribution = Form::selectRaw('priority, COUNT(*) as count')
            ->whereNotNull('priority')
            ->groupBy('priority')
            ->get();
            
        // Top Requesting Clients
        $topClients = User::where('role', 'client')
            ->withCount('forms')
            ->orderBy('forms_count', 'desc')
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
            'total_size' => Document::sum('file_size'),
            'uploaded_this_period' => Document::where('created_at', '>=', $startDate)->count(),
            'size_this_period' => Document::where('created_at', '>=', $startDate)->sum('file_size'),
            'average_size' => Document::avg('file_size'),
        ];
        
        // Upload Trend
        $uploadTrend = Document::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(file_size) as total_size')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Type Distribution
        $typeDistribution = Document::selectRaw('type, COUNT(*) as count, SUM(file_size) as total_size')
            ->groupBy('type')
            ->get();
            
        // Category Distribution
        $categoryDistribution = Document::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get();
            
        // Top Uploaders
        $topUploaders = User::withCount('uploadedDocuments')
            ->orderBy('uploaded_documents_count', 'desc')
            ->take(10)
            ->get();
            
        // Storage Usage by Client
        $storageByClient = User::where('role', 'client')
            ->leftJoin('documents', 'users.id', '=', 'documents.client_id')
            ->selectRaw('users.fullName, users.id, COALESCE(SUM(documents.file_size), 0) as total_size, COUNT(documents.id) as document_count')
            ->groupBy('users.id', 'users.fullName')
            ->orderBy('total_size', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.reports.documents', compact(
            'stats', 'uploadTrend', 'typeDistribution', 'categoryDistribution', 'topUploaders', 'storageByClient', 'period'
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
                $data = Task::with(['client', 'adiutor'])->where('created_at', '>=', $startDate)->get();
                $headers = ['ID', 'Title', 'Client', 'Assignee', 'Status', 'Priority', 'Created At', 'Due Date'];
                $callback = function() use ($data, $headers) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $headers);
                    foreach ($data as $task) {
                        fputcsv($file, [
                            $task->id,
                            $task->title,
                            $task->client->fullName ?? 'N/A',
                            $task->adiutor->fullName ?? 'Unassigned',
                            $task->status,
                            $task->priority,
                            $task->created_at->format('Y-m-d H:i:s'),
                            $task->due_date ?? 'N/A',
                        ]);
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
}