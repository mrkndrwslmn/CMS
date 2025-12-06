<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\RecaptchaService;
use App\Services\EmailSenderService;
use App\Events\TierUpgraded;
use App\Listeners\SendTierUpgradeNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register reCAPTCHA service
        $this->app->singleton(RecaptchaService::class, function ($app) {
            return new RecaptchaService();
        });

        // Register Email Sender service
        $this->app->singleton(EmailSenderService::class, function ($app) {
            return new EmailSenderService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners
        Event::listen(
            TierUpgraded::class,
            SendTierUpgradeNotification::class
        );

        // Share announcements and sidebar stats with client views (cached for 1 minute)
        View::composer('client.layouts.app', function ($view) {
            $announcements = $this->getCachedAnnouncements('client');
            $sidebarStats = $this->getClientSidebarStats();
            $view->with([
                'announcements' => $announcements,
                'sidebarStats' => $sidebarStats,
            ]);
        });

        // Share announcements with adiutor views (cached for 5 minutes)
        View::composer('adiutor.layouts.app', function ($view) {
            $announcements = $this->getCachedAnnouncements('adiutor');
            $view->with('announcements', $announcements);
        });

        // Share announcements with public views (except login) (cached for 5 minutes)
        View::composer(['public.*', '!public.login'], function ($view) {
            $announcements = $this->getCachedAnnouncements('public');
            $view->with('announcements', $announcements);
        });
    }

    /**
     * Get cached sidebar statistics for client layout
     * Reduces ~10 DB queries per page load to 1 cached call
     */
    private function getClientSidebarStats(): array
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return [
                'pendingRevisionsCount' => 0,
                'unreadNotificationsCount' => 0,
                'pendingReferralsCount' => 0,
                'activeCouponsCount' => 0,
                'userPoints' => 0,
            ];
        }

        $cacheKey = "client_sidebar_stats_{$user->id}";
        
        return Cache::remember($cacheKey, 60, function () use ($user) {
            // Get pending revisions count through projects owned by the client
            $pendingRevisionsCount = DB::table('revision_requests')
                ->join('projects', 'revision_requests.project_id', '=', 'projects.id')
                ->where('projects.client_id', $user->id)
                ->whereIn('revision_requests.status', ['pending', 'approved'])
                ->count();
            
            return [
                'pendingRevisionsCount' => $pendingRevisionsCount,
                'unreadNotificationsCount' => DB::table('notifications')
                    ->where('notifiable_id', $user->id)
                    ->where('notifiable_type', 'App\\Models\\User')
                    ->whereNull('read_at')
                    ->count(),
                'pendingReferralsCount' => DB::table('referrals')
                    ->where('referrer_id', $user->id)
                    ->where('status', 'pending')
                    ->count(),
                'activeCouponsCount' => DB::table('loyalty_coupons')
                    ->where('client_id', $user->id)
                    ->where('is_used', false)
                    ->count(),
                'userPoints' => $user->loyalty_points ?? 0,
            ];
        });
    }

    /**
     * Get cached announcements for a specific audience
     */
    private function getCachedAnnouncements(string $audience)
    {
        $cacheKey = "announcements_{$audience}";
        
        return Cache::remember($cacheKey, 300, function () use ($audience) {
            return DB::table('announcements')
                ->join('users', 'announcements.created_by', '=', 'users.id')
                ->where('announcements.status', 'active')
                ->where(function ($query) use ($audience) {
                    $query->where('announcements.target_audience', 'LIKE', "%{$audience}%")
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
                ->get()
                ->map(function ($announcement) {
                    $announcement->created_at = \Carbon\Carbon::parse($announcement->created_at);
                    if ($announcement->starts_at) {
                        $announcement->starts_at = \Carbon\Carbon::parse($announcement->starts_at);
                    }
                    if ($announcement->expires_at) {
                        $announcement->expires_at = \Carbon\Carbon::parse($announcement->expires_at);
                    }
                    return $announcement;
                });
        });
    }
}
