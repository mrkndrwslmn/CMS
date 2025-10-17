<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Maya Payment Gateway Environment
    |--------------------------------------------------------------------------
    |
    | This value determines which environment to use for Maya payments.
    | Options: 'sandbox' or 'production'
    |
    */
    'environment' => env('MAYA_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Maya Sandbox Credentials
    |--------------------------------------------------------------------------
    |
    | These are your Maya sandbox API credentials for testing.
    |
    */
    'sandbox' => [
        'public_key' => env('MAYA_SANDBOX_PUBLIC_KEY', 'pk-Hz7gJ5GU1tVXlch5THxl0GhOhnKsuKy7ojmVndNgwa9'),
        'secret_key' => env('MAYA_SANDBOX_SECRET_KEY', 'sk-aUZlKwBgAxImdHplcrytmee1XCo6zWJHlBBPtT3MI1t'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maya Production Credentials
    |--------------------------------------------------------------------------
    |
    | These are your Maya production API credentials for live transactions.
    | IMPORTANT: Keep these credentials secure and never commit them to version control.
    |
    */
    'production' => [
        'public_key' => env('MAYA_PRODUCTION_PUBLIC_KEY', ''),
        'secret_key' => env('MAYA_PRODUCTION_SECRET_KEY', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Additional configuration for payment processing
    |
    */
    'currency' => env('MAYA_CURRENCY', 'PHP'),
    
    'webhook_secret' => env('MAYA_WEBHOOK_SECRET', ''),
];
