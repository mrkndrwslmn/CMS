<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        
        // Trust all proxies (for dev tunnels and reverse proxies)
        $middleware->trustProxies(at: '*');
        
        // Add global middleware to update announcement statuses
        $middleware->append(\App\Http\Middleware\UpdateAnnouncementStatuses::class);
        
        // Exclude API routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'referral/validate',  // Public referral validation for registration
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
