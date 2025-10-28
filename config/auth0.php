<?php

declare(strict_types=1);

use Auth0\Laravel\Configuration;
use Auth0\SDK\Configuration\SdkConfiguration;

return Configuration::VERSION_2 + [
    'registerGuards' => false, // Don't register guards, use existing Laravel auth
    'registerMiddleware' => false, // Don't register middleware
    'registerAuthenticationRoutes' => false, // Don't register Auth0 routes - we'll handle them manually
    'configurationPath' => null,

    'guards' => [
        'default' => [
            Configuration::CONFIG_STRATEGY => SdkConfiguration::STRATEGY_NONE,
            Configuration::CONFIG_DOMAIN => env('AUTH0_DOMAIN'),
            Configuration::CONFIG_CLIENT_ID => env('AUTH0_CLIENT_ID'),
            Configuration::CONFIG_CLIENT_SECRET => env('AUTH0_CLIENT_SECRET'),
            Configuration::CONFIG_AUDIENCE => env('AUTH0_AUDIENCE'),
            Configuration::CONFIG_SCOPE => env('AUTH0_SCOPE', 'openid profile email'),
        ],

        'web' => [
            Configuration::CONFIG_STRATEGY => SdkConfiguration::STRATEGY_REGULAR,
            Configuration::CONFIG_COOKIE_SECRET => env('APP_KEY'),
            Configuration::CONFIG_REDIRECT_URI => env('AUTH0_CALLBACK_URL', env('APP_URL') . '/auth0/callback'),
            Configuration::CONFIG_SESSION_STORAGE => Configuration::get(Configuration::CONFIG_SESSION_STORAGE),
            Configuration::CONFIG_SESSION_STORAGE_ID => Configuration::get(Configuration::CONFIG_SESSION_STORAGE_ID),
            Configuration::CONFIG_TRANSIENT_STORAGE => Configuration::get(Configuration::CONFIG_TRANSIENT_STORAGE),
            Configuration::CONFIG_TRANSIENT_STORAGE_ID => Configuration::get(Configuration::CONFIG_TRANSIENT_STORAGE_ID),
        ],
    ],

    'routes' => [
        Configuration::CONFIG_ROUTE_INDEX => '/',
        Configuration::CONFIG_ROUTE_CALLBACK => '/auth0/callback',
        Configuration::CONFIG_ROUTE_LOGIN => '/auth0/login',
        Configuration::CONFIG_ROUTE_AFTER_LOGIN => null, // We'll handle this in our callback
        Configuration::CONFIG_ROUTE_LOGOUT => '/auth0/logout',
        Configuration::CONFIG_ROUTE_AFTER_LOGOUT => '/',
    ],

    // Social login configuration
    'socialProviders' => [
        'google' => env('AUTH0_GOOGLE_ENABLED', true),
        'apple' => env('AUTH0_APPLE_ENABLED', true),
        'twitter' => env('AUTH0_TWITTER_ENABLED', true),
    ],

    'userRepository' => App\Repositories\Auth0UserRepository::class,
];