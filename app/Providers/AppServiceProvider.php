<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
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
    }
}
