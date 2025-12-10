<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformEarning;
use App\Models\Project;
use App\Services\PlatformEarningsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Carbon;

class PlatformEarningsController extends Controller
{
    protected PlatformEarningsService $earningsService;

    public function __construct(PlatformEarningsService $earningsService)
    {
        $this->earningsService = $earningsService;
    }

    /**
     * Display platform earnings dashboard.
     */
    public function index(Request $request)
    {
        // Get dashboard stats from service
        $rawStats = $this->earningsService->getDashboardStats();
        
        // Map to view-expected variable names
        $stats = [
            'total_revenue' => $rawStats['total_platform_revenue'] ?? 0,
            'this_month_revenue' => $rawStats['this_month_revenue'] ?? 0,
            'revenue_growth' => $rawStats['monthly_growth'] ?? 0,
            'total_platform_fees' => $rawStats['total_platform_fee'] ?? 0,
            'total_margin' => $rawStats['total_margin_earnings'] ?? 0,
            'total_adiutor_costs' => $rawStats['total_adiutor_cost'] ?? 0,
            'avg_profit_margin' => $rawStats['average_profit_margin'] ?? 0,
            'projects_count' => $rawStats['total_projects'] ?? 0,
        ];

        // Get revenue by period (default: monthly for current year)
        $period = $request->input('period', 'monthly');
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $revenueData = $this->earningsService->getRevenueByPeriod($period, 12);
        
        // Transform to array format expected by view
        $revenueData = $revenueData->map(function ($item) {
            return [
                'period' => $item->period,
                'platform_fee' => (float) $item->platform_fee,
                'margin' => (float) $item->margin_earnings,
                'total' => (float) $item->total_revenue,
            ];
        })->toArray();

        // Get project breakdown (paginated)
        $projectStatus = $request->input('project_status');
        $projectBreakdown = PlatformEarning::with('project.client')
            ->when($projectStatus, function ($query, $status) {
                $query->whereHas('project', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            })
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('admin.platform-earnings.index', compact(
            'stats',
            'revenueData',
            'projectBreakdown',
            'period',
            'year',
            'month'
        ));
    }

    /**
     * Show earnings details for a specific project.
     */
    public function show(Project $project)
    {
        $earnings = $project->platformEarnings;

        // Calculate earnings if not exists
        if (!$earnings) {
            $this->earningsService->getOrCreateForProject($project);
            $earnings = $project->fresh()->platformEarnings;
        }

        // Get adiutor assignments for cost breakdown
        $assignments = $project->assignments()->with('adiutor')->get();

        // Build adiutor breakdown for the view
        $adiutorBreakdown = [];
        
        foreach ($assignments as $assignment) {
            $adiutor = $assignment->adiutor;
            if (!$adiutor) continue;
            
            $adiutorId = $adiutor->id;
            
            if (!isset($adiutorBreakdown[$adiutorId])) {
                $adiutorBreakdown[$adiutorId] = [
                    'name' => $adiutor->fullName ?? $adiutor->name ?? 'Unknown',
                    'type' => $assignment->payment_type === 'fixed_rate' ? 'Fixed Rate' : 'Hourly',
                    'total' => 0,
                ];
            }
            
            // Add fixed rate cost
            if ($assignment->payment_type === 'fixed_rate' && $assignment->fixed_rate_approved) {
                $adiutorBreakdown[$adiutorId]['total'] += (float) $assignment->agreed_rate;
            }
        }
        
        // Get time entries for hourly earnings
        $timeEntries = $project->timeEntries()
            ->with('user')
            ->where('is_approved', true)
            ->get();
            
        foreach ($timeEntries as $entry) {
            $user = $entry->user;
            if (!$user) continue;
            
            $userId = $user->id;
            
            if (!isset($adiutorBreakdown[$userId])) {
                $adiutorBreakdown[$userId] = [
                    'name' => $user->fullName ?? $user->name ?? 'Unknown',
                    'type' => 'Hourly',
                    'total' => 0,
                ];
            } else {
                // Has both fixed and hourly
                $adiutorBreakdown[$userId]['type'] = 'Mixed';
            }
            
            $adiutorBreakdown[$userId]['total'] += (float) $entry->calculated_amount;
        }
        
        $adiutorBreakdown = array_values($adiutorBreakdown);

        return view('admin.platform-earnings.show', compact(
            'project',
            'earnings',
            'adiutorBreakdown'
        ));
    }

    /**
     * Calculate and store platform earnings for a project.
     */
    public function calculate(Project $project)
    {
        $earnings = $this->earningsService->calculateOrCreate($project);

        return redirect()
            ->route('admin.platform-earnings.show', $project)
            ->with('success', 'Platform earnings calculated successfully. Total: ₱' . number_format($earnings->total_earnings, 2));
    }

    /**
     * Finalize earnings for a completed project.
     */
    public function finalize(Project $project)
    {
        // Only allow finalizing completed projects
        if ($project->status !== 'completed') {
            return back()->with('error', 'Can only finalize earnings for completed projects.');
        }

        $earnings = $this->earningsService->finalizeProjectEarnings($project);

        if (!$earnings) {
            return back()->with('error', 'Failed to finalize earnings. Please calculate earnings first.');
        }

        return redirect()
            ->route('admin.platform-earnings.show', $project)
            ->with('success', 'Earnings finalized successfully.');
    }

    /**
     * Bulk recalculate earnings for all projects.
     */
    public function recalculateAll(Request $request)
    {
        $status = $request->input('status'); // Optional: filter by project status

        $query = Project::query();
        if ($status) {
            $query->where('status', $status);
        }

        $projects = $query->get();
        $count = 0;

        foreach ($projects as $project) {
            $this->earningsService->calculateOrCreate($project);
            $count++;
        }

        return back()->with('success', "Recalculated earnings for {$count} projects.");
    }

    /**
     * Export platform earnings to CSV.
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : now()->startOfYear();
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : now();

        $status = $request->input('status');

        $query = PlatformEarning::with('project')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($status) {
            $query->where('status', $status);
        }

        $earnings = $query->orderBy('created_at', 'desc')->get();

        $filename = 'platform_earnings_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($earnings) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Project ID',
                'Project Title',
                'Budget',
                'Platform Fee',
                'Margin Earnings',
                'Total Earnings',
                'Adiutor Costs',
                'Time Costs',
                'Status',
                'Calculated At',
                'Finalized At',
            ]);

            // Data rows
            foreach ($earnings as $earning) {
                fputcsv($file, [
                    $earning->project_id,
                    $earning->project->title ?? 'N/A',
                    number_format($earning->project->budget ?? 0, 2),
                    number_format($earning->platform_fee, 2),
                    number_format($earning->margin_earnings, 2),
                    number_format($earning->total_earnings, 2),
                    number_format($earning->total_adiutor_cost, 2),
                    number_format($earning->total_time_cost, 2),
                    $earning->status,
                    $earning->created_at->format('Y-m-d H:i'),
                    $earning->finalized_at?->format('Y-m-d H:i') ?? '',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Get summary report for a period.
     */
    public function report(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $revenueData = $this->earningsService->getRevenueByPeriod($period, $year, $month);
        $stats = $this->earningsService->getDashboardStats();

        // Get top earning projects
        $topProjects = PlatformEarning::with('project')
            ->finalized()
            ->orderByRaw('platform_fee + margin_earnings DESC')
            ->limit(10)
            ->get();

        return view('admin.platform-earnings.report', compact(
            'revenueData',
            'stats',
            'topProjects',
            'period',
            'year',
            'month'
        ));
    }
}
