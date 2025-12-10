<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\ServiceRequest;
use App\Models\Project;
use App\Models\Conversation;
use App\Services\DashboardStatsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    use \App\Http\Controllers\Admin\AdminMessagingMethods;

    protected DashboardStatsService $statsService;

    public function __construct(DashboardStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Show admin dashboard
     * OPTIMIZED: Uses cached stats and batched queries
     */
    public function dashboard()
    {
        // Get cached dashboard statistics (reduces 7+ queries to 1 cached call)
        $cachedStats = $this->statsService->getAdminStats();
        
        // Add recent items (these are small queries, acceptable to run fresh)
        $stats = array_merge($cachedStats, [
            'recent_users' => User::select('id', 'fullName', 'email', 'role', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'recent_requests' => ServiceRequest::with('user:id,fullName,email')
                ->select('id', 'client_id', 'service_type', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ]);

        // Cache chart data for 5 minutes (doesn't change frequently)
        $monthlyUsers = Cache::remember('admin_monthly_users_chart', 300, function () {
            return User::selectRaw('DATE_FORMAT(created_at, "%m") as month, COUNT(*) as count')
                ->whereRaw('YEAR(created_at) = ?', [date('Y')])
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        });

        $taskStats = Cache::remember('admin_task_stats_chart', 300, function () {
            return Task::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();
        });

        // Get pending budget change requests with optimized eager loading
        $pendingBudgetRequests = \App\Models\BudgetChangeRequest::with([
                'task:taskID,taskTitle,project_id', 
                'adiutor:id,fullName'
            ])
            ->where('status', 'pending')
            ->select('id', 'task_id', 'adiutor_id', 'current_budget', 'requested_budget', 'reason', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Earnings Quick Stats (Phase 7) - cached
        $earningsStats = $this->getEarningsQuickStats();

        return view('admin.dashboard', compact('stats', 'monthlyUsers', 'taskStats', 'pendingBudgetRequests', 'earningsStats'));
    }

    /**
     * Refresh dashboard data via AJAX
     * OPTIMIZED: Uses cached stats service
     */
    public function refreshDashboard()
    {
        // Invalidate cache to get fresh data
        $this->statsService->invalidateAdminStats();
        Cache::forget('admin_monthly_users_chart');
        Cache::forget('admin_task_stats_chart');
        
        // Get fresh statistics
        $cachedStats = $this->statsService->getAdminStats();
        $stats = array_merge($cachedStats, [
            'recent_users' => User::select('id', 'fullName', 'email', 'role', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'recent_requests' => ServiceRequest::with('user:id,fullName,email')
                ->select('id', 'client_id', 'service_type', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ]);

        $monthlyUsers = User::selectRaw('DATE_FORMAT(created_at, "%m") as month, COUNT(*) as count')
            ->whereRaw('YEAR(created_at) = ?', [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

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
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phoneNumber' => ['nullable', 'string', 'max:25', new \App\Rules\PhoneNumber(true)],
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

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            try {
                $r2Service = app(\App\Services\CloudflareR2Service::class);
                $result = $r2Service->uploadProfilePicture($request->file('profile_picture'), $user->id);
                
                if ($result['success']) {
                    User::where('id', $user->id)->update([
                        'profilePic' => $result['url'],
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Admin profile picture upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to upload profile picture. Please try again.');
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
        
        // Calculate total project budgets (what clients paid)
        $totalProjectBudgets = $completedProjects->sum(function ($project) {
            if ($project->serviceRequest) {
                $approved = $project->serviceRequest->approved_budget ?? $project->budget ?? 0;
                $discount = $project->serviceRequest->total_discount_amount ?? 0;
                return max(0, $approved - $discount);
            }
            return $project->budget ?? 0;
        });
        
        // Get platform earnings stats
        $platformEarnings = \App\Models\PlatformEarning::query();
        $totalPlatformRevenue = (clone $platformEarnings)->sum('total_platform_revenue') ?: 0;
        $totalPlatformFees = (clone $platformEarnings)->sum('platform_fee') ?: 0;
        $totalMarginEarnings = (clone $platformEarnings)->sum('margin_earnings') ?: 0;
        
        // This month's platform revenue
        $thisMonthPlatformRevenue = \App\Models\PlatformEarning::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_platform_revenue') ?: 0;
        
        // Last month's platform revenue for comparison
        $lastMonthPlatformRevenue = \App\Models\PlatformEarning::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_platform_revenue') ?: 0;
        
        // Calculate month-over-month growth using platform revenue
        $monthlyGrowth = $lastMonthPlatformRevenue > 0 
            ? round((($thisMonthPlatformRevenue - $lastMonthPlatformRevenue) / $lastMonthPlatformRevenue) * 100, 1)
            : ($thisMonthPlatformRevenue > 0 ? 100 : 0);

        // Adiutor costs this month
        $adiutorEarningsThisMonth = \App\Models\TimeEntry::where('is_approved', true)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('calculated_amount') ?? 0;
        
        // Add fixed rate approvals this month
        $fixedRateThisMonth = \App\Models\ProjectAssignment::where('payment_type', 'fixed_rate')
            ->where('fixed_rate_approved', true)
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('agreed_rate') ?? 0;
        
        $totalAdiutorCostsThisMonth = (float) $adiutorEarningsThisMonth + (float) $fixedRateThisMonth;

        return [
            'total_earnings' => $totalPlatformRevenue > 0 ? $totalPlatformRevenue : $totalProjectBudgets, // Use platform revenue if available
            'total_project_budgets' => $totalProjectBudgets,
            'total_platform_revenue' => $totalPlatformRevenue,
            'total_platform_fees' => $totalPlatformFees,
            'total_margin_earnings' => $totalMarginEarnings,
            'this_month_earnings' => $thisMonthPlatformRevenue > 0 ? $thisMonthPlatformRevenue : 0,
            'monthly_growth' => $monthlyGrowth,
            'pending_approvals' => \App\Models\TimeEntry::where('is_approved', false)
                ->whereNotNull('end_time')
                ->count(),
            'pending_payouts' => \App\Models\Payout::where('status', 'pending')
                ->sum('amount'),
            'adiutor_earnings_this_month' => $totalAdiutorCostsThisMonth,
            'pending_hour_requests' => \App\Models\HourIncreaseRequest::where('status', 'pending')
                ->count(),
            'completed_projects' => $completedProjects->count(),
            'active_projects' => \App\Models\Project::whereIn('status', ['active', 'in_progress'])->count(),
        ];
    }
}