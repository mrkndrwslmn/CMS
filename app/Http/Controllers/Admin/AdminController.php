<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\ServiceRequest;
use App\Models\Project;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    use \App\Http\Controllers\Admin\AdminMessagingMethods;
    /**
     * Show admin login form.
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->role === 'admin' && $user->status === 'active') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            } else {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Unauthorized access or inactive account.',
                ]);
            }
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Get dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_adiutors' => User::where('role', 'adiutor')->count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'active_tasks' => Task::whereIn('status', ['pending', 'in_progress'])->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'pending_budget_requests' => \App\Models\BudgetChangeRequest::where('status', 'pending')->count(),
            'recent_users' => User::orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_requests' => ServiceRequest::with('user')->orderBy('created_at', 'desc')->limit(5)->get(),
        ];

        // Get monthly user registrations for chart
        $monthlyUsers = User::selectRaw('DATE_FORMAT(created_at, "%m") as month, COUNT(*) as count')
            ->whereRaw('YEAR(created_at) = ?', [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get task completion stats for chart
        $taskStats = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Get pending budget change requests
        $pendingBudgetRequests = \App\Models\BudgetChangeRequest::with(['task', 'adiutor'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Earnings Quick Stats (Phase 7)
        $earningsStats = $this->getEarningsQuickStats();

        return view('admin.dashboard', compact('stats', 'monthlyUsers', 'taskStats', 'pendingBudgetRequests', 'earningsStats'));
    }

    /**
     * Refresh dashboard data via AJAX
     */
    public function refreshDashboard()
    {
        // Get fresh dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_adiutors' => User::where('role', 'adiutor')->count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'active_tasks' => Task::whereIn('status', ['pending', 'in_progress'])->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'pending_budget_requests' => \App\Models\BudgetChangeRequest::where('status', 'pending')->count(),
            'recent_users' => User::orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_requests' => ServiceRequest::with('user')->orderBy('created_at', 'desc')->limit(5)->get(),
        ];

        // Get monthly user registrations for chart
        $monthlyUsers = User::selectRaw('DATE_FORMAT(created_at, "%m") as month, COUNT(*) as count')
            ->whereRaw('YEAR(created_at) = ?', [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get task completion stats for chart
        $taskStats = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Dashboard data refreshed successfully',
            'data' => [
                'stats' => $stats,
                'monthlyUsers' => $monthlyUsers,
                'taskStats' => $taskStats,
                'timestamp' => now()->format('F d, Y \a\t g:i A')
            ]
        ]);
    }

    /**
     * Download dashboard report
     */
    public function downloadReport(Request $request)
    {
        $timeFilter = $request->get('filter', 'This Year');
        
        // Generate report data based on time filter
        $reportData = $this->generateReportData($timeFilter);
        
        // Create CSV content
        $csvContent = $this->generateCSVContent($reportData, $timeFilter);
        
        $filename = 'dashboard_report_' . strtolower(str_replace(' ', '_', $timeFilter)) . '_' . date('Y-m-d') . '.csv';
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Generate report data based on time filter
     */
    private function generateReportData($timeFilter)
    {
        $data = [];
        
        switch ($timeFilter) {
            case 'This Month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'Last Month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            case 'Last 3 Months':
                $startDate = now()->subMonths(3)->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'This Year':
            default:
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
        }
        
        // Generate comprehensive statistics
        $data['summary'] = [
            'total_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_clients' => User::where('role', 'client')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_adiutors' => User::where('role', 'adiutor')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_tasks' => Task::whereIn('status', ['pending', 'in_progress'])->whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_tasks' => Task::where('status', 'completed')->whereBetween('updated_at', [$startDate, $endDate])->count(),
        ];
        
        // Monthly breakdown
        $data['monthly_users'] = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $data['monthly_requests'] = ServiceRequest::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $data['task_status_breakdown'] = Task::selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();
        
        $data['period'] = [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d'),
            'filter' => $timeFilter
        ];
        
        return $data;
    }

    /**
     * Generate CSV content from report data
     */
    private function generateCSVContent($data, $timeFilter)
    {
        $csv = [];
        
        // Header
        $csv[] = ['Dashboard Report - ' . $timeFilter];
        $csv[] = ['Generated on: ' . now()->format('F d, Y \a\t g:i A')];
        $csv[] = ['Period: ' . $data['period']['start'] . ' to ' . $data['period']['end']];
        $csv[] = [''];
        
        // Summary Statistics
        $csv[] = ['SUMMARY STATISTICS'];
        $csv[] = ['Metric', 'Count'];
        $csv[] = ['Total Users', $data['summary']['total_users']];
        $csv[] = ['Total Clients', $data['summary']['total_clients']];
        $csv[] = ['Total Adiutors', $data['summary']['total_adiutors']];
        $csv[] = ['Pending Requests', $data['summary']['pending_requests']];
        $csv[] = ['Active Tasks', $data['summary']['active_tasks']];
        $csv[] = ['Completed Tasks', $data['summary']['completed_tasks']];
        $csv[] = [''];
        
        // Monthly User Registrations
        $csv[] = ['MONTHLY USER REGISTRATIONS'];
        $csv[] = ['Month', 'Count'];
        foreach ($data['monthly_users'] as $monthData) {
            $csv[] = [$monthData->month, $monthData->count];
        }
        $csv[] = [''];
        
        // Monthly Service Requests
        $csv[] = ['MONTHLY SERVICE REQUESTS'];
        $csv[] = ['Month', 'Count'];
        foreach ($data['monthly_requests'] as $requestData) {
            $csv[] = [$requestData->month, $requestData->count];
        }
        $csv[] = [''];
        
        // Task Status Breakdown
        $csv[] = ['TASK STATUS BREAKDOWN'];
        $csv[] = ['Status', 'Count'];
        foreach ($data['task_status_breakdown'] as $taskData) {
            $csv[] = [ucfirst($taskData->status), $taskData->count];
        }
        
        // Convert to CSV string
        $output = fopen('php://temp', 'r+');
        foreach ($csv as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);
        
        return $csvContent;
    }

    /**
     * Show user management
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users', compact('users'));
    }

    /**
     * Show service requests management
     */
    public function requests()
    {
        $requests = ServiceRequest::with('user')->orderBy('submission_date', 'desc')->paginate(15);
        
        return view('admin.requests', compact('requests'));
    }

    /**
     * Show task assignment
     */
    public function tasks()
    {
        $tasks = Task::with(['project', 'assignedUser', 'client'])->orderBy('dateAssigned', 'desc')->paginate(15);
        
        return view('admin.tasks', compact('tasks'));
    }

    /**
     * Show reports and analytics
     */
    public function reports()
    {
        // Generate comprehensive analytics data
        $analytics = [
            'user_growth' => User::selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
                ->whereRaw('strftime("%Y", created_at) = ?', [date('Y')])
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'task_completion' => Task::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'user_roles' => User::selectRaw('role, COUNT(*) as count')
                ->groupBy('role')
                ->get(),
            'monthly_requests' => ServiceRequest::selectRaw('strftime("%m", submission_date) as month, COUNT(*) as count')
                ->whereRaw('strftime("%Y", submission_date) = ?', [date('Y')])
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];
        
        return view('admin.reports', compact('analytics'));
    }

    /**
     * Show admin profile.
     */
    public function profile()
    {
        return view('admin.profile', ['user' => Auth::user()]);
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phoneNumber' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Check current password if new password is provided
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Current password is incorrect.',
                ]);
            }
        }

        // Update user data
        $userData = $request->only(['fullName', 'email', 'phoneNumber']);
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        User::where('id', $user->id)->update($userData);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Get earnings quick stats for dashboard widget
     */
    private function getEarningsQuickStats()
    {
        // Get completed projects with their service requests to calculate actual revenue (after discounts)
        $completedProjects = \App\Models\Project::where('status', 'completed')
            ->with('serviceRequest')
            ->get();
        
        // Calculate total earnings (approved_budget - discount from service request)
        $totalProjectEarnings = $completedProjects->sum(function ($project) {
            if ($project->serviceRequest) {
                // Use approved_budget minus any discounts applied
                $approved = $project->serviceRequest->approved_budget ?? $project->budget ?? 0;
                $discount = $project->serviceRequest->total_discount_amount ?? 0;
                return max(0, $approved - $discount);
            }
            return $project->budget ?? 0;
        });
        
        // Get this month's completed projects
        $thisMonthProjects = $completedProjects->filter(function ($project) {
            return $project->completed_at && $project->completed_at->isCurrentMonth();
        });
        
        $thisMonthProjectEarnings = $thisMonthProjects->sum(function ($project) {
            if ($project->serviceRequest) {
                $approved = $project->serviceRequest->approved_budget ?? $project->budget ?? 0;
                $discount = $project->serviceRequest->total_discount_amount ?? 0;
                return max(0, $approved - $discount);
            }
            return $project->budget ?? 0;
        });
        
        // Get last month's completed projects for comparison
        $lastMonthProjects = $completedProjects->filter(function ($project) {
            return $project->completed_at && $project->completed_at->month === now()->subMonth()->month 
                && $project->completed_at->year === now()->subMonth()->year;
        });
        
        $lastMonthProjectEarnings = $lastMonthProjects->sum(function ($project) {
            if ($project->serviceRequest) {
                $approved = $project->serviceRequest->approved_budget ?? $project->budget ?? 0;
                $discount = $project->serviceRequest->total_discount_amount ?? 0;
                return max(0, $approved - $discount);
            }
            return $project->budget ?? 0;
        });
        
        // Calculate month-over-month growth
        $monthlyGrowth = $lastMonthProjectEarnings > 0 
            ? round((($thisMonthProjectEarnings - $lastMonthProjectEarnings) / $lastMonthProjectEarnings) * 100, 1)
            : ($thisMonthProjectEarnings > 0 ? 100 : 0);

        return [
            'total_earnings' => $totalProjectEarnings,
            'this_month_earnings' => $thisMonthProjectEarnings,
            'monthly_growth' => $monthlyGrowth,
            'pending_approvals' => \App\Models\TimeEntry::where('is_approved', false)
                ->whereNotNull('end_time')
                ->count(),
            'pending_payouts' => \App\Models\Payout::where('status', 'pending')
                ->sum('amount'),
            'adiutor_earnings_this_month' => \App\Models\TimeEntry::where('is_approved', true)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('calculated_amount'),
            'pending_hour_requests' => \App\Models\HourIncreaseRequest::where('status', 'pending')
                ->count(),
            'completed_projects' => $completedProjects->count(),
            'active_projects' => \App\Models\Project::whereIn('status', ['active', 'in_progress'])->count(),
        ];
    }
}