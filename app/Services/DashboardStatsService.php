<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Centralized dashboard statistics service with caching
 * Optimizes database queries by batching and caching results
 */
class DashboardStatsService
{
    /**
     * Get admin dashboard stats with caching (refreshes every 2 minutes)
     */
    public function getAdminStats(): array
    {
        return Cache::remember('admin_dashboard_stats', 120, function () {
            // Single query for user counts by role
            $userCounts = DB::table('users')
                ->selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN role = 'client' THEN 1 ELSE 0 END) as clients,
                    SUM(CASE WHEN role = 'adiutor' THEN 1 ELSE 0 END) as adiutors
                ")
                ->first();

            // Single query for task counts by status
            $taskCounts = DB::table('tasks')
                ->selectRaw("
                    SUM(CASE WHEN status IN ('pending', 'in_progress') THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                ")
                ->first();

            // Single query for pending counts
            $pendingCounts = DB::table('service_requests')
                ->where('status', 'pending')
                ->count();

            $pendingBudgetRequests = DB::table('budget_change_requests')
                ->where('status', 'pending')
                ->count();

            return [
                'total_users' => $userCounts->total ?? 0,
                'total_clients' => $userCounts->clients ?? 0,
                'total_adiutors' => $userCounts->adiutors ?? 0,
                'pending_requests' => $pendingCounts,
                'active_tasks' => $taskCounts->active ?? 0,
                'completed_tasks' => $taskCounts->completed ?? 0,
                'pending_budget_requests' => $pendingBudgetRequests,
            ];
        });
    }

    /**
     * Get client dashboard stats with caching (user-specific, refreshes every minute)
     */
    public function getClientStats(int $userId): array
    {
        return Cache::remember("client_dashboard_stats_{$userId}", 60, function () use ($userId) {
            // Batch project counts in single query
            $projectCounts = DB::table('projects')
                ->where('client_id', $userId)
                ->selectRaw("
                    SUM(CASE WHEN status IN ('active', 'in_progress', 'review') THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                ")
                ->first();

            // Pending requests count
            $pendingRequests = DB::table('service_requests')
                ->where('client_id', $userId)
                ->where('status', 'pending')
                ->count();

            // Total spent
            $totalSpent = DB::table('payments')
                ->where('client_id', $userId)
                ->where('status', 'confirmed')
                ->sum('amount') ?? 0;

            return [
                'activeProjects' => $projectCounts->active ?? 0,
                'completedProjects' => $projectCounts->completed ?? 0,
                'pendingRequests' => $pendingRequests,
                'totalSpent' => $totalSpent,
            ];
        });
    }

    /**
     * Get adiutor dashboard stats with caching (user-specific, refreshes every minute)
     */
    public function getAdiutorStats(int $userId): array
    {
        return Cache::remember("adiutor_dashboard_stats_{$userId}", 60, function () use ($userId) {
            // Batch assignment counts in single query
            $assignmentCounts = DB::table('project_assignments')
                ->where('adiutor_id', $userId)
                ->selectRaw("
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'completed' THEN COALESCE(agreed_rate, 0) ELSE 0 END) as total_earnings
                ")
                ->first();

            // Batch task counts in single query with proper join
            $taskCounts = DB::table('tasks')
                ->join('project_assignments', 'tasks.project_id', '=', 'project_assignments.project_id')
                ->where('project_assignments.adiutor_id', $userId)
                ->where('tasks.assignedTo', $userId)
                ->selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN tasks.status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN tasks.status IN ('pending', 'in_progress') THEN 1 ELSE 0 END) as pending
                ")
                ->first();

            return [
                'active_projects' => $assignmentCounts->active ?? 0,
                'completed_projects' => $assignmentCounts->completed ?? 0,
                'pending_assignments' => $assignmentCounts->pending ?? 0,
                'total_earnings' => $assignmentCounts->total_earnings ?? 0,
                'total_tasks' => $taskCounts->total ?? 0,
                'completed_tasks' => $taskCounts->completed ?? 0,
                'pending_tasks' => $taskCounts->pending ?? 0,
            ];
        });
    }

    /**
     * Invalidate admin stats cache
     */
    public function invalidateAdminStats(): void
    {
        Cache::forget('admin_dashboard_stats');
    }

    /**
     * Invalidate client stats cache
     */
    public function invalidateClientStats(int $userId): void
    {
        Cache::forget("client_dashboard_stats_{$userId}");
    }

    /**
     * Invalidate adiutor stats cache
     */
    public function invalidateAdiutorStats(int $userId): void
    {
        Cache::forget("adiutor_dashboard_stats_{$userId}");
    }
}
