<?php

namespace App\Providers;

use App\Repositories\Auth0UserRepository;
use Auth0\Login\Contract\LoginContract;
use Illuminate\Support\ServiceProvider;

class Auth0ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the Auth0 user repository
        $this->app->bind(LoginContract::class, Auth0UserRepository::class);
        $this->app->bind(Auth0UserRepository::class, Auth0UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Additional Auth0 configuration can go here
    }
}
