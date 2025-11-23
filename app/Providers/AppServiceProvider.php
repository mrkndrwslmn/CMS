<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
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

        // Share announcements with client views
        View::composer('client.layouts.app', function ($view) {
            $announcements = DB::table('announcements')
                ->join('users', 'announcements.created_by', '=', 'users.id')
                ->where('announcements.status', 'active')
                ->where(function ($query) {
                    $query->where('announcements.target_audience', 'LIKE', '%client%')
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

            $view->with('announcements', $announcements);
        });

        // Share announcements with adiutor views
        View::composer('adiutor.layouts.app', function ($view) {
            $announcements = DB::table('announcements')
                ->join('users', 'announcements.created_by', '=', 'users.id')
                ->where('announcements.status', 'active')
                ->where(function ($query) {
                    $query->where('announcements.target_audience', 'LIKE', '%adiutor%')
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
                ->get();

            $view->with('announcements', $announcements);
        });

        // Share announcements with public views (except login)
        View::composer(['public.*', '!public.login'], function ($view) {
            $announcements = DB::table('announcements')
                ->join('users', 'announcements.created_by', '=', 'users.id')
                ->where('announcements.status', 'active')
                ->where(function ($query) {
                    $query->where('announcements.target_audience', 'LIKE', '%public%')
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
                ->get();

            $view->with('announcements', $announcements);
        });
    }
}
