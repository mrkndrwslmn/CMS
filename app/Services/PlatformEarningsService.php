<?php

namespace App\Services;

use App\Models\PlatformEarning;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\TimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlatformEarningsService
{
    /**
     * Get the platform fee percentage from config
     */
    public function getFeePercentage(): float
    {
        return (float) config('financial.platform.fee_percentage', 15);
    }

    /**
     * Get or create platform earning record for a project
     */
    public function getOrCreateForProject(Project $project): PlatformEarning
    {
        return PlatformEarning::firstOrCreate(
            ['project_id' => $project->id],
            $this->calculateForProject($project)
        );
    }

    /**
     * Calculate and create/update platform earnings for a project
     * Alias for getOrCreateForProject for backwards compatibility
     */
    public function calculateOrCreate(Project $project): PlatformEarning
    {
        $existing = PlatformEarning::where('project_id', $project->id)->first();
        
        if ($existing) {
            // If not finalized, recalculate
            if (!$existing->isFinalized()) {
                $data = $this->calculateForProject($project);
                $existing->update($data);
                return $existing->fresh();
            }
            return $existing;
        }
        
        // Create new
        return PlatformEarning::create($this->calculateForProject($project));
    }

    /**
     * Calculate platform earnings for a project
     */
    public function calculateForProject(Project $project): array
    {
        // Get project budget
        $projectBudget = (float) ($project->budget ?? 0);
        
        // Get client payment total (from payments table)
        $clientPayment = DB::table('payments')
            ->where('service_request_id', $project->service_request_id)
            ->where('status', 'confirmed')
            ->sum('amount') ?? $projectBudget;
        
        // Calculate platform fee
        $feePercentage = $this->getFeePercentage();
        $fixedFee = config('financial.platform.fixed_fee');
        $minimumFee = (float) config('financial.platform.minimum_fee', 500);
        
        if ($fixedFee && $fixedFee > 0) {
            $platformFee = (float) $fixedFee;
        } else {
            $platformFee = $projectBudget * ($feePercentage / 100);
            // Ensure minimum fee
            $platformFee = max($platformFee, $minimumFee);
        }
        
        // Working budget (what's available for adiutors after platform fee)
        $workingBudget = $projectBudget - $platformFee;
        
        // Calculate adiutor costs
        // 1. Hourly earnings (from approved time entries)
        $hourlyCost = DB::table('time_entries')
            ->join('tasks', 'time_entries.task_id', '=', 'tasks.taskID')
            ->where('tasks.project_id', $project->id)
            ->where('time_entries.is_approved', true)
            ->sum('time_entries.calculated_amount') ?? 0;
        
        // 2. Fixed rate earnings (from approved project assignments)
        $fixedRateCost = DB::table('project_assignments')
            ->where('project_id', $project->id)
            ->where('payment_type', 'fixed_rate')
            ->where('fixed_rate_approved', true)
            ->sum('agreed_rate') ?? 0;
        
        $totalAdiutorCost = (float) $hourlyCost + (float) $fixedRateCost;
        
        // Calculate margin earnings (what's left after paying adiutors)
        $marginEarnings = max(0, $workingBudget - $totalAdiutorCost);
        
        // Total platform revenue
        $totalPlatformRevenue = $platformFee + $marginEarnings;
        
        // Task allocation tracking
        $totalTaskAllocated = DB::table('tasks')
            ->where('project_id', $project->id)
            ->sum('allocated_budget') ?? 0;
        
        $unallocatedBudget = max(0, $workingBudget - (float) $totalTaskAllocated);
        
        // Determine status
        $status = PlatformEarning::STATUS_PENDING;
        if ($project->status === 'completed') {
            $status = PlatformEarning::STATUS_FINALIZED;
        } elseif (in_array($project->status, ['active', 'in_progress'])) {
            $status = PlatformEarning::STATUS_IN_PROGRESS;
        }
        
        return [
            'project_id' => $project->id,
            'platform_fee' => $platformFee,
            'margin_earnings' => $marginEarnings,
            'total_platform_revenue' => $totalPlatformRevenue,
            'client_payment' => (float) $clientPayment,
            'project_budget' => $projectBudget,
            'working_budget' => $workingBudget,
            'total_adiutor_cost' => $totalAdiutorCost,
            'hourly_cost' => (float) $hourlyCost,
            'fixed_rate_cost' => (float) $fixedRateCost,
            'fee_percentage' => $feePercentage,
            'total_task_allocated' => (float) $totalTaskAllocated,
            'unallocated_budget' => $unallocatedBudget,
            'status' => $status,
        ];
    }

    /**
     * Recalculate and update platform earnings for a project
     */
    public function recalculateForProject(Project $project): PlatformEarning
    {
        $earning = $this->getOrCreateForProject($project);
        
        // Don't recalculate if already finalized
        if ($earning->isFinalized()) {
            return $earning;
        }
        
        $data = $this->calculateForProject($project);
        $earning->update($data);
        
        return $earning->fresh();
    }

    /**
     * Finalize platform earnings for a completed project
     */
    public function finalizeProjectEarnings(Project $project, ?int $userId = null): PlatformEarning
    {
        $earning = $this->recalculateForProject($project);
        
        $earning->update([
            'status' => PlatformEarning::STATUS_FINALIZED,
            'finalized_at' => now(),
            'finalized_by' => $userId,
        ]);
        
        return $earning->fresh();
    }

    /**
     * Adjust finalized earnings
     */
    public function adjustEarnings(PlatformEarning $earning, float $adjustmentAmount, string $reason, int $userId): PlatformEarning
    {
        $earning->update([
            'adjustment_amount' => $adjustmentAmount,
            'adjustment_reason' => $reason,
            'adjusted_by' => $userId,
            'adjusted_at' => now(),
            'status' => PlatformEarning::STATUS_ADJUSTED,
        ]);
        
        return $earning->fresh();
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = PlatformEarning::query();
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        // All time stats (no date filter)
        $allTimeQuery = PlatformEarning::query();
        
        // Calculate totals
        $totalPlatformFee = (clone $query)->sum('platform_fee');
        $totalMarginEarnings = (clone $query)->sum('margin_earnings');
        $totalPlatformRevenue = (clone $query)->sum('total_platform_revenue');
        $totalAdjustments = (clone $query)->sum('adjustment_amount');
        $totalAdiutorCost = (clone $query)->sum('total_adiutor_cost');
        
        // Finalized vs pending
        $finalizedRevenue = (clone $query)->finalized()->sum('total_platform_revenue');
        $pendingRevenue = (clone $query)->where('status', '!=', PlatformEarning::STATUS_FINALIZED)->sum('total_platform_revenue');
        
        // Count projects
        $totalProjects = (clone $query)->count();
        $finalizedProjects = (clone $query)->finalized()->count();
        
        // This month stats
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthQuery = PlatformEarning::where('created_at', '>=', $thisMonthStart);
        $thisMonthRevenue = $thisMonthQuery->sum('total_platform_revenue');
        $thisMonthProjects = $thisMonthQuery->count();
        
        // Last month for comparison
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $lastMonthQuery = PlatformEarning::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd]);
        $lastMonthRevenue = $lastMonthQuery->sum('total_platform_revenue');
        
        // Growth calculation
        $monthlyGrowth = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($thisMonthRevenue > 0 ? 100 : 0);
        
        // Average profit margin
        $avgProfitMargin = $totalProjects > 0 
            ? $allTimeQuery->avg(DB::raw('(total_platform_revenue / NULLIF(project_budget, 0)) * 100')) ?? 0
            : 0;
        
        return [
            'total_platform_fee' => (float) $totalPlatformFee,
            'total_margin_earnings' => (float) $totalMarginEarnings,
            'total_platform_revenue' => (float) $totalPlatformRevenue,
            'total_adjustments' => (float) $totalAdjustments,
            'final_revenue' => (float) $totalPlatformRevenue + (float) $totalAdjustments,
            'total_adiutor_cost' => (float) $totalAdiutorCost,
            'finalized_revenue' => (float) $finalizedRevenue,
            'pending_revenue' => (float) $pendingRevenue,
            'total_projects' => $totalProjects,
            'finalized_projects' => $finalizedProjects,
            'this_month_revenue' => (float) $thisMonthRevenue,
            'this_month_projects' => $thisMonthProjects,
            'last_month_revenue' => (float) $lastMonthRevenue,
            'monthly_growth' => $monthlyGrowth,
            'average_profit_margin' => round((float) $avgProfitMargin, 2),
        ];
    }

    /**
     * Get revenue by period for charts
     */
    public function getRevenueByPeriod(string $period = 'monthly', ?int $limit = 12): Collection
    {
        $dateFormat = match($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            'yearly' => '%Y',
            default => '%Y-%m',
        };
        
        return PlatformEarning::select([
            DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
            DB::raw('SUM(platform_fee) as platform_fee'),
            DB::raw('SUM(margin_earnings) as margin_earnings'),
            DB::raw('SUM(total_platform_revenue) as total_revenue'),
            DB::raw('SUM(total_adiutor_cost) as adiutor_cost'),
            DB::raw('COUNT(*) as project_count'),
        ])
            ->groupBy('period')
            ->orderByDesc('period')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Get top earning projects
     */
    public function getTopProjects(?int $limit = 10, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $query = PlatformEarning::with('project.client')
            ->orderByDesc('total_platform_revenue');
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        return $query->limit($limit)->get();
    }

    /**
     * Get earnings summary by status
     */
    public function getEarningsByStatus(): array
    {
        $results = PlatformEarning::select('status')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(total_platform_revenue) as revenue')
            ->selectRaw('SUM(total_adiutor_cost) as adiutor_cost')
            ->groupBy('status')
            ->get()
            ->keyBy('status');
        
        return [
            'pending' => [
                'count' => $results->get(PlatformEarning::STATUS_PENDING)?->count ?? 0,
                'revenue' => (float) ($results->get(PlatformEarning::STATUS_PENDING)?->revenue ?? 0),
            ],
            'in_progress' => [
                'count' => $results->get(PlatformEarning::STATUS_IN_PROGRESS)?->count ?? 0,
                'revenue' => (float) ($results->get(PlatformEarning::STATUS_IN_PROGRESS)?->revenue ?? 0),
            ],
            'finalized' => [
                'count' => $results->get(PlatformEarning::STATUS_FINALIZED)?->count ?? 0,
                'revenue' => (float) ($results->get(PlatformEarning::STATUS_FINALIZED)?->revenue ?? 0),
            ],
            'adjusted' => [
                'count' => $results->get(PlatformEarning::STATUS_ADJUSTED)?->count ?? 0,
                'revenue' => (float) ($results->get(PlatformEarning::STATUS_ADJUSTED)?->revenue ?? 0),
            ],
        ];
    }

    /**
     * Sync all existing projects (for migration purposes)
     */
    public function syncAllProjects(): array
    {
        $projects = Project::all();
        $synced = 0;
        $errors = [];
        
        foreach ($projects as $project) {
            try {
                $this->getOrCreateForProject($project);
                $synced++;
            } catch (\Exception $e) {
                $errors[] = "Project {$project->id}: {$e->getMessage()}";
            }
        }
        
        return [
            'synced' => $synced,
            'errors' => $errors,
        ];
    }
}
