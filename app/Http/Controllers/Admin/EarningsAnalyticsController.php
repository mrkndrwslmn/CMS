<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\TimeEntry;
use App\Models\Payout;
use App\Models\WalletTransaction;
use App\Models\HourIncreaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EarningsAnalyticsController extends Controller
{
    /**
     * Display the earnings analytics dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        // Summary Stats
        $stats = $this->getSummaryStats($startDate, $endDate);
        
        // Earnings Trend (Daily/Weekly based on period)
        $earningsTrend = $this->getEarningsTrend($startDate, $endDate);
        
        // Top Earners
        $topEarners = $this->getTopEarners($startDate, $endDate, 10);
        
        // Pending Approvals Count
        $pendingApprovals = [
            'time_entries' => TimeEntry::pending()->completed()->count(),
            'fixed_rates' => ProjectAssignment::where('payment_type', 'fixed_rate')
                ->where('fixed_rate_approved', false)
                ->where('status', 'completed')
                ->count(),
            'hour_requests' => HourIncreaseRequest::pending()->count(),
            'payout_requests' => Payout::where('status', 'pending')->count(),
        ];

        // Payment Type Distribution
        $paymentTypeDistribution = $this->getPaymentTypeDistribution($startDate, $endDate);
        
        // Project Cost Analysis
        $projectCostAnalysis = $this->getProjectCostAnalysis($startDate, $endDate, 10);
        
        // Recent Payouts
        $recentPayouts = Payout::with(['adiutor:id,fullName,email', 'processedBy:id,fullName'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.earnings-analytics.index', compact(
            'stats',
            'earningsTrend',
            'topEarners',
            'pendingApprovals',
            'paymentTypeDistribution',
            'projectCostAnalysis',
            'recentPayouts',
            'period',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Detailed adiutor leaderboard view
     */
    public function leaderboard(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();
        $sortBy = $request->get('sort', 'earnings');
        $perPage = $request->get('per_page', 20);

        $query = User::where('role', 'adiutor')
            ->where('status', 'active')
            ->withCount(['projectAssignments as total_assignments'])
            ->with(['adiutorProfile:id,user_id,standard_hourly_rate']);

        // Add subqueries for earnings and hours
        $query->addSelect([
            'users.*',
            DB::raw("(
                SELECT COALESCE(SUM(te.calculated_amount), 0) 
                FROM time_entries te 
                WHERE te.adiutor_id = users.id 
                AND te.is_approved = 1 
                AND te.end_time IS NOT NULL
                AND te.created_at BETWEEN '{$startDate->format('Y-m-d H:i:s')}' AND '{$endDate->format('Y-m-d H:i:s')}'
            ) as hourly_earnings"),
            DB::raw("(
                SELECT COALESCE(SUM(pa.agreed_rate), 0) 
                FROM project_assignments pa 
                WHERE pa.adiutor_id = users.id 
                AND pa.payment_type = 'fixed_rate'
                AND pa.fixed_rate_approved = 1
                AND pa.fixed_rate_approved_at BETWEEN '{$startDate->format('Y-m-d H:i:s')}' AND '{$endDate->format('Y-m-d H:i:s')}'
            ) as fixed_earnings"),
            DB::raw("(
                SELECT COALESCE(SUM(te.duration_minutes), 0) / 60 
                FROM time_entries te 
                WHERE te.adiutor_id = users.id 
                AND te.end_time IS NOT NULL
                AND te.created_at BETWEEN '{$startDate->format('Y-m-d H:i:s')}' AND '{$endDate->format('Y-m-d H:i:s')}'
            ) as total_hours"),
        ]);

        // Sorting
        switch ($sortBy) {
            case 'hours':
                $query->orderByDesc('total_hours');
                break;
            case 'assignments':
                $query->orderByDesc('total_assignments');
                break;
            case 'earnings':
            default:
                $query->orderByRaw('(hourly_earnings + fixed_earnings) DESC');
                break;
        }

        $adiutors = $query->paginate($perPage);

        // Calculate totals for summary
        $totals = [
            'total_earnings' => TimeEntry::where('is_approved', true)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('calculated_amount') +
                ProjectAssignment::where('payment_type', 'fixed_rate')
                    ->where('fixed_rate_approved', true)
                    ->whereBetween('fixed_rate_approved_at', [$startDate, $endDate])
                    ->sum('agreed_rate'),
            'total_hours' => TimeEntry::whereNotNull('end_time')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('duration_minutes') / 60,
            'total_adiutors' => User::where('role', 'adiutor')->where('status', 'active')->count(),
            'active_adiutors' => User::where('role', 'adiutor')
                ->whereHas('timeEntries', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->count(),
        ];

        return view('admin.earnings-analytics.leaderboard', compact(
            'adiutors',
            'totals',
            'period',
            'sortBy',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Project cost analysis detailed view
     */
    public function projectCosts(Request $request)
    {
        $period = $request->get('period', 'all');
        $startDate = $period === 'all' ? null : $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();
        $perPage = $request->get('per_page', 20);

        $query = Project::with(['client:id,fullName', 'assignments.adiutor:id,fullName'])
            ->withCount('assignments')
            ->select('projects.*');

        // Add budget utilization calculations
        $query->addSelect([
            DB::raw("(
                SELECT COALESCE(SUM(te.calculated_amount), 0) 
                FROM time_entries te 
                INNER JOIN project_assignments pa ON pa.project_id = projects.id AND pa.adiutor_id = te.adiutor_id
                WHERE te.project_id = projects.id 
                AND te.is_approved = 1
            ) as hourly_cost"),
            DB::raw("(
                SELECT COALESCE(SUM(pa.agreed_rate), 0) 
                FROM project_assignments pa 
                WHERE pa.project_id = projects.id 
                AND pa.payment_type = 'fixed_rate'
                AND pa.fixed_rate_approved = 1
            ) as fixed_cost"),
            DB::raw("(
                SELECT COALESCE(SUM(te.duration_minutes), 0) / 60 
                FROM time_entries te 
                WHERE te.project_id = projects.id 
                AND te.end_time IS NOT NULL
            ) as total_hours"),
        ]);

        if ($startDate) {
            $query->where('projects.created_at', '>=', $startDate);
        }

        $projects = $query->orderByDesc(DB::raw('hourly_cost + fixed_cost'))
            ->paginate($perPage);

        // Summary stats
        $summary = [
            'total_projects' => Project::count(),
            'total_budget' => Project::sum('budget'),
            'total_spent' => TimeEntry::where('is_approved', true)->sum('calculated_amount') +
                ProjectAssignment::where('fixed_rate_approved', true)->sum('agreed_rate'),
            'average_cost_per_project' => Project::count() > 0 
                ? (TimeEntry::where('is_approved', true)->sum('calculated_amount') +
                   ProjectAssignment::where('fixed_rate_approved', true)->sum('agreed_rate')) / Project::count()
                : 0,
            // Platform earnings summary
            'total_platform_fee' => \App\Models\PlatformEarning::sum('platform_fee') ?: 0,
            'total_margin' => \App\Models\PlatformEarning::sum('margin_earnings') ?: 0,
            'total_platform_revenue' => \App\Models\PlatformEarning::sum('total_platform_revenue') ?: 0,
            'fee_percentage' => config('financial.platform.fee_percentage', 15),
        ];

        return view('admin.earnings-analytics.project-costs', compact(
            'projects',
            'summary',
            'period',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Time entry audit log
     */
    public function auditLog(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();
        $filter = $request->get('filter', 'all'); // all, adjusted, approved, pending
        $adiutorId = $request->get('adiutor_id');
        $projectId = $request->get('project_id');
        $perPage = $request->get('per_page', 25);

        $query = TimeEntry::with([
            'adiutor:id,fullName,email',
            'project:id,title',
            'task:taskID,taskTitle',
            'approver:id,fullName',
            'adjuster:id,fullName',
        ])
        ->whereNotNull('end_time')
        ->whereBetween('created_at', [$startDate, $endDate]);

        // Apply filters
        switch ($filter) {
            case 'adjusted':
                $query->where('admin_adjusted', true);
                break;
            case 'approved':
                $query->where('is_approved', true);
                break;
            case 'pending':
                $query->where('is_approved', false);
                break;
        }

        if ($adiutorId) {
            $query->where('adiutor_id', $adiutorId);
        }

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $timeEntries = $query->latest()->paginate($perPage);

        // Stats
        $stats = [
            'total_entries' => TimeEntry::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('end_time')->count(),
            'adjusted_entries' => TimeEntry::where('admin_adjusted', true)->whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending_entries' => TimeEntry::where('is_approved', false)->whereNotNull('end_time')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_hours' => TimeEntry::whereNotNull('end_time')->whereBetween('created_at', [$startDate, $endDate])->sum('duration_minutes') / 60,
            'total_amount' => TimeEntry::where('is_approved', true)->whereBetween('created_at', [$startDate, $endDate])->sum('calculated_amount'),
        ];

        // Get adiutors and projects for filter dropdowns
        $adiutors = User::where('role', 'adiutor')->select('id', 'fullName')->orderBy('fullName')->get();
        $projects = Project::select('id', 'title')->orderBy('title')->get();

        return view('admin.earnings-analytics.audit-log', compact(
            'timeEntries',
            'stats',
            'filter',
            'adiutorId',
            'projectId',
            'adiutors',
            'projects',
            'period',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Payout history with detailed breakdown
     */
    public function payoutHistory(Request $request)
    {
        $period = $request->get('period', '90');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();
        $status = $request->get('status');
        $adiutorId = $request->get('adiutor_id');
        $perPage = $request->get('per_page', 20);

        $query = Payout::with([
            'adiutor:id,fullName,email',
            'processedBy:id,fullName',
            'items',
        ])
        ->whereBetween('created_at', [$startDate, $endDate]);

        if ($status) {
            $query->where('status', $status);
        }

        if ($adiutorId) {
            $query->where('adiutor_id', $adiutorId);
        }

        $payouts = $query->latest()->paginate($perPage);

        // Summary stats
        $stats = [
            'total_payouts' => Payout::whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_payouts' => Payout::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending_payouts' => Payout::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_paid' => Payout::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('amount'),
            'pending_amount' => Payout::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->sum('amount'),
            'average_payout' => Payout::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->avg('amount'),
        ];

        // Monthly trend
        $monthFormat = $this->getDateFormatExpression('created_at', '%Y-%m');
        $monthlyTrend = Payout::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("{$monthFormat} as month, SUM(amount) as total, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get adiutors for filter dropdown
        $adiutors = User::where('role', 'adiutor')->select('id', 'fullName')->orderBy('fullName')->get();

        return view('admin.earnings-analytics.payout-history', compact(
            'payouts',
            'stats',
            'monthlyTrend',
            'status',
            'adiutorId',
            'adiutors',
            'period',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export earnings data
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'earnings');
        $period = $request->get('period', '30');
        $startDate = $this->getStartDate($period, $request);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();
        $format = $request->get('format', 'csv');

        $filename = "earnings_{$type}_" . now()->format('Y-m-d_H-i-s') . ".{$format}";

        switch ($type) {
            case 'time_entries':
                return $this->exportTimeEntries($startDate, $endDate, $filename);
            case 'payouts':
                return $this->exportPayouts($startDate, $endDate, $filename);
            case 'adiutor_earnings':
                return $this->exportAdiutorEarnings($startDate, $endDate, $filename);
            case 'project_costs':
                return $this->exportProjectCosts($startDate, $endDate, $filename);
            default:
                return $this->exportEarningsSummary($startDate, $endDate, $filename);
        }
    }

    // ==========================================
    // PRIVATE HELPER METHODS
    // ==========================================

    /**
     * Get database-agnostic date formatting expression
     */
    private function getDateFormatExpression(string $column, string $format): string
    {
        $driver = config('database.default');
        $connection = config("database.connections.{$driver}.driver", $driver);

        if ($connection === 'sqlite') {
            // SQLite uses strftime
            $sqliteFormat = match ($format) {
                '%Y-%m' => '%Y-%m',
                '%Y-%u' => '%Y-%W',  // Week number
                '%Y-%m-%d' => '%Y-%m-%d',
                default => $format,
            };
            return "strftime('{$sqliteFormat}', {$column})";
        }

        // MySQL/MariaDB uses DATE_FORMAT
        return "DATE_FORMAT({$column}, '{$format}')";
    }

    private function getStartDate($period, Request $request)
    {
        if ($period === 'custom' && $request->has('start_date')) {
            return Carbon::parse($request->get('start_date'));
        }

        return Carbon::now()->subDays((int) $period);
    }

    private function getSummaryStats($startDate, $endDate)
    {
        // Hourly earnings (approved time entries)
        $hourlyEarnings = TimeEntry::where('is_approved', true)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('calculated_amount');

        // Fixed rate earnings (approved fixed rate payments)
        $fixedRateEarnings = ProjectAssignment::where('payment_type', 'fixed_rate')
            ->where('fixed_rate_approved', true)
            ->whereBetween('fixed_rate_approved_at', [$startDate, $endDate])
            ->sum('agreed_rate');

        // Pending hourly earnings
        $pendingHourlyEarnings = TimeEntry::where('is_approved', false)
            ->whereNotNull('end_time')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('calculated_amount');

        // Pending fixed rate earnings
        $pendingFixedEarnings = ProjectAssignment::where('payment_type', 'fixed_rate')
            ->where('fixed_rate_approved', false)
            ->where('status', 'completed')
            ->sum('agreed_rate');

        // Total hours tracked
        $totalHours = TimeEntry::whereNotNull('end_time')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('duration_minutes') / 60;

        // Billable hours only
        $billableHours = TimeEntry::whereNotNull('end_time')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('billable_minutes') / 60;

        // Payouts
        $totalPaidOut = Payout::where('status', 'completed')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->sum('amount');

        $pendingPayouts = Payout::where('status', 'pending')
            ->sum('amount');

        // Active adiutors in period
        $activeAdiutors = TimeEntry::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('adiutor_id')
            ->count('adiutor_id');

        return [
            'total_earnings' => $hourlyEarnings + $fixedRateEarnings,
            'hourly_earnings' => $hourlyEarnings,
            'fixed_rate_earnings' => $fixedRateEarnings,
            'pending_earnings' => $pendingHourlyEarnings + $pendingFixedEarnings,
            'total_hours' => round($totalHours, 2),
            'billable_hours' => round($billableHours, 2),
            'non_billable_hours' => round($totalHours - $billableHours, 2),
            'total_paid_out' => $totalPaidOut,
            'pending_payouts' => $pendingPayouts,
            'active_adiutors' => $activeAdiutors,
            'average_hourly_rate' => $billableHours > 0 ? round($hourlyEarnings / $billableHours, 2) : 0,
        ];
    }

    private function getEarningsTrend($startDate, $endDate)
    {
        $days = $startDate->diffInDays($endDate);
        $groupBy = $days > 60 ? 'week' : 'day';

        if ($groupBy === 'week') {
            $format = '%Y-%u';
            $labelFormat = 'Week %u, %Y';
        } else {
            $format = '%Y-%m-%d';
            $labelFormat = 'M d';
        }

        $dateFormatExpr = $this->getDateFormatExpression('created_at', $format);
        $approvedAtFormatExpr = $this->getDateFormatExpression('fixed_rate_approved_at', $format);

        // Hourly earnings trend
        $hourlyTrend = TimeEntry::where('is_approved', true)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("{$dateFormatExpr} as period, SUM(calculated_amount) as amount, SUM(duration_minutes)/60 as hours")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        // Fixed rate trend
        $fixedTrend = ProjectAssignment::where('payment_type', 'fixed_rate')
            ->where('fixed_rate_approved', true)
            ->whereBetween('fixed_rate_approved_at', [$startDate, $endDate])
            ->selectRaw("{$approvedAtFormatExpr} as period, SUM(agreed_rate) as amount")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        // Combine into trend data
        $allPeriods = $hourlyTrend->keys()->merge($fixedTrend->keys())->unique()->sort();

        return $allPeriods->map(function($period) use ($hourlyTrend, $fixedTrend) {
            return [
                'period' => $period,
                'hourly_earnings' => $hourlyTrend->get($period)?->amount ?? 0,
                'fixed_earnings' => $fixedTrend->get($period)?->amount ?? 0,
                'total_earnings' => ($hourlyTrend->get($period)?->amount ?? 0) + ($fixedTrend->get($period)?->amount ?? 0),
                'hours' => $hourlyTrend->get($period)?->hours ?? 0,
            ];
        })->values();
    }

    private function getTopEarners($startDate, $endDate, $limit = 10)
    {
        return User::where('role', 'adiutor')
            ->select('users.id', 'users.fullName', 'users.email', 'users.profilePic')
            ->addSelect([
                DB::raw("(
                    SELECT COALESCE(SUM(te.calculated_amount), 0) 
                    FROM time_entries te 
                    WHERE te.adiutor_id = users.id 
                    AND te.is_approved = 1 
                    AND te.created_at BETWEEN '{$startDate->format('Y-m-d H:i:s')}' AND '{$endDate->format('Y-m-d H:i:s')}'
                ) as hourly_earnings"),
                DB::raw("(
                    SELECT COALESCE(SUM(pa.agreed_rate), 0) 
                    FROM project_assignments pa 
                    WHERE pa.adiutor_id = users.id 
                    AND pa.payment_type = 'fixed_rate'
                    AND pa.fixed_rate_approved = 1
                    AND pa.fixed_rate_approved_at BETWEEN '{$startDate->format('Y-m-d H:i:s')}' AND '{$endDate->format('Y-m-d H:i:s')}'
                ) as fixed_earnings"),
                DB::raw("(
                    SELECT COALESCE(SUM(te.duration_minutes), 0) / 60 
                    FROM time_entries te 
                    WHERE te.adiutor_id = users.id 
                    AND te.end_time IS NOT NULL
                    AND te.created_at BETWEEN '{$startDate->format('Y-m-d H:i:s')}' AND '{$endDate->format('Y-m-d H:i:s')}'
                ) as total_hours"),
            ])
            ->orderByRaw('(hourly_earnings + fixed_earnings) DESC')
            ->take($limit)
            ->get()
            ->map(function($user) {
                $user->total_earnings = $user->hourly_earnings + $user->fixed_earnings;
                return $user;
            });
    }

    private function getPaymentTypeDistribution($startDate, $endDate)
    {
        $hourlyEarnings = TimeEntry::where('is_approved', true)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('calculated_amount');

        $fixedEarnings = ProjectAssignment::where('payment_type', 'fixed_rate')
            ->where('fixed_rate_approved', true)
            ->whereBetween('fixed_rate_approved_at', [$startDate, $endDate])
            ->sum('agreed_rate');

        $hourlyAssignments = ProjectAssignment::where('payment_type', 'hourly_rate')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $fixedAssignments = ProjectAssignment::where('payment_type', 'fixed_rate')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'hourly' => [
                'earnings' => $hourlyEarnings,
                'assignments' => $hourlyAssignments,
                'percentage' => ($hourlyEarnings + $fixedEarnings) > 0 
                    ? round(($hourlyEarnings / ($hourlyEarnings + $fixedEarnings)) * 100, 1) 
                    : 0,
            ],
            'fixed' => [
                'earnings' => $fixedEarnings,
                'assignments' => $fixedAssignments,
                'percentage' => ($hourlyEarnings + $fixedEarnings) > 0 
                    ? round(($fixedEarnings / ($hourlyEarnings + $fixedEarnings)) * 100, 1) 
                    : 0,
            ],
        ];
    }

    private function getProjectCostAnalysis($startDate, $endDate, $limit = 10)
    {
        return Project::select('projects.id', 'projects.title', 'projects.budget', 'projects.status')
            ->addSelect([
                DB::raw("(
                    SELECT COALESCE(SUM(te.calculated_amount), 0) 
                    FROM time_entries te 
                    WHERE te.project_id = projects.id 
                    AND te.is_approved = 1
                ) as labor_cost"),
                DB::raw("(
                    SELECT COALESCE(SUM(pa.agreed_rate), 0) 
                    FROM project_assignments pa 
                    WHERE pa.project_id = projects.id 
                    AND pa.payment_type = 'fixed_rate'
                    AND pa.fixed_rate_approved = 1
                ) as fixed_cost"),
                DB::raw("(
                    SELECT COUNT(DISTINCT pa.adiutor_id) 
                    FROM project_assignments pa 
                    WHERE pa.project_id = projects.id
                ) as adiutor_count"),
            ])
            ->whereBetween('projects.created_at', [$startDate, $endDate])
            ->orderByRaw('(labor_cost + fixed_cost) DESC')
            ->take($limit)
            ->get()
            ->map(function($project) {
                $project->total_cost = $project->labor_cost + $project->fixed_cost;
                $project->budget_utilization = $project->budget > 0 
                    ? round(($project->total_cost / $project->budget) * 100, 1) 
                    : 0;
                return $project;
            });
    }

    // ==========================================
    // EXPORT METHODS
    // ==========================================

    private function exportTimeEntries($startDate, $endDate, $filename)
    {
        $data = TimeEntry::with(['adiutor:id,fullName', 'project:id,title', 'task:taskID,taskTitle'])
            ->whereNotNull('end_time')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $headers = ['ID', 'Adiutor', 'Project', 'Task', 'Start Time', 'End Time', 'Duration (hrs)', 'Billable (hrs)', 'Rate', 'Amount', 'Approved', 'Adjusted', 'Created At'];

        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($data as $entry) {
                fputcsv($file, [
                    $entry->id,
                    $entry->adiutor?->fullName ?? 'N/A',
                    $entry->project?->title ?? 'N/A',
                    $entry->task?->taskTitle ?? 'N/A',
                    $entry->start_time?->format('Y-m-d H:i:s'),
                    $entry->end_time?->format('Y-m-d H:i:s'),
                    round($entry->duration_minutes / 60, 2),
                    round(($entry->billable_minutes ?? $entry->duration_minutes) / 60, 2),
                    '₱' . number_format($entry->hourly_rate, 2),
                    '₱' . number_format($entry->calculated_amount, 2),
                    $entry->is_approved ? 'Yes' : 'No',
                    $entry->admin_adjusted ? 'Yes' : 'No',
                    $entry->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportPayouts($startDate, $endDate, $filename)
    {
        $data = Payout::with(['adiutor:id,fullName', 'processedBy:id,fullName'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $headers = ['Payout #', 'Adiutor', 'Amount', 'Status', 'Method', 'Reference', 'Requested At', 'Processed By', 'Completed At'];

        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($data as $payout) {
                fputcsv($file, [
                    $payout->payout_number,
                    $payout->adiutor?->fullName ?? 'N/A',
                    '₱' . number_format($payout->amount, 2),
                    ucfirst($payout->status),
                    $payout->payout_method ?? 'N/A',
                    $payout->reference_number ?? 'N/A',
                    $payout->requested_at?->format('Y-m-d H:i:s'),
                    $payout->processedBy?->fullName ?? 'N/A',
                    $payout->completed_at?->format('Y-m-d H:i:s') ?? 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportAdiutorEarnings($startDate, $endDate, $filename)
    {
        $data = $this->getTopEarners($startDate, $endDate, 1000);

        $headers = ['Adiutor', 'Email', 'Hourly Earnings', 'Fixed Earnings', 'Total Earnings', 'Total Hours'];

        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($data as $adiutor) {
                fputcsv($file, [
                    $adiutor->fullName,
                    $adiutor->email,
                    '₱' . number_format($adiutor->hourly_earnings, 2),
                    '₱' . number_format($adiutor->fixed_earnings, 2),
                    '₱' . number_format($adiutor->total_earnings, 2),
                    round($adiutor->total_hours, 2),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportProjectCosts($startDate, $endDate, $filename)
    {
        $data = $this->getProjectCostAnalysis($startDate, $endDate, 1000);

        $headers = ['Project', 'Budget', 'Labor Cost', 'Fixed Cost', 'Total Cost', 'Budget Utilization %', 'Status', 'Adiutors'];

        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($data as $project) {
                fputcsv($file, [
                    $project->title,
                    '₱' . number_format($project->budget ?? 0, 2),
                    '₱' . number_format($project->labor_cost, 2),
                    '₱' . number_format($project->fixed_cost, 2),
                    '₱' . number_format($project->total_cost, 2),
                    $project->budget_utilization . '%',
                    ucfirst($project->status),
                    $project->adiutor_count,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportEarningsSummary($startDate, $endDate, $filename)
    {
        $stats = $this->getSummaryStats($startDate, $endDate);

        $headers = ['Metric', 'Value'];

        $callback = function() use ($stats, $headers, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fputcsv($file, ['Report Period', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')]);
            fputcsv($file, ['Total Earnings', '₱' . number_format($stats['total_earnings'], 2)]);
            fputcsv($file, ['Hourly Earnings', '₱' . number_format($stats['hourly_earnings'], 2)]);
            fputcsv($file, ['Fixed Rate Earnings', '₱' . number_format($stats['fixed_rate_earnings'], 2)]);
            fputcsv($file, ['Pending Earnings', '₱' . number_format($stats['pending_earnings'], 2)]);
            fputcsv($file, ['Total Hours Tracked', $stats['total_hours'] . ' hrs']);
            fputcsv($file, ['Billable Hours', $stats['billable_hours'] . ' hrs']);
            fputcsv($file, ['Non-Billable Hours', $stats['non_billable_hours'] . ' hrs']);
            fputcsv($file, ['Total Paid Out', '₱' . number_format($stats['total_paid_out'], 2)]);
            fputcsv($file, ['Pending Payouts', '₱' . number_format($stats['pending_payouts'], 2)]);
            fputcsv($file, ['Active Adiutors', $stats['active_adiutors']]);
            fputcsv($file, ['Average Hourly Rate', '₱' . number_format($stats['average_hourly_rate'], 2)]);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
