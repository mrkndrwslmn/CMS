<?php

return [
    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google reCAPTCHA integration
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable reCAPTCHA
    |--------------------------------------------------------------------------
    |
    | Set to false to disable reCAPTCHA entirely (useful for development)
    |
    */
    'enabled' => env('RECAPTCHA_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Keys
    |--------------------------------------------------------------------------
    |
    | Your reCAPTCHA site key and secret key from Google reCAPTCHA console
    | https://www.google.com/recaptcha/admin
    |
    */
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Version
    |--------------------------------------------------------------------------
    |
    | Supported values: 'v2', 'v3'
    | v2: Shows a checkbox for user interaction
    | v3: Invisible, analyzes user behavior and gives a score
    |
    */
    'version' => env('RECAPTCHA_VERSION', 'v2'),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA v2 Settings
    |--------------------------------------------------------------------------
    |
    | Theme: 'light' or 'dark'
    | Size: 'normal', 'compact'
    |
    */
    'theme' => env('RECAPTCHA_THEME', 'light'),
    'size' => env('RECAPTCHA_SIZE', 'normal'),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA v3 Settings
    |--------------------------------------------------------------------------
    |
    | Minimum score threshold (0.0 to 1.0)
    | Higher scores indicate more likely human interaction
    | Recommended: 0.5 for forms, 0.7 for sensitive actions
    |
    */
    'min_score' => env('RECAPTCHA_MIN_SCORE', 0.5),

    /*
    |--------------------------------------------------------------------------
    | Error Handling
    |--------------------------------------------------------------------------
    |
    | fail_open: If true, allows requests when reCAPTCHA service is unavailable
    | If false, blocks requests when service fails (more secure)
    |
    */
    'fail_open' => env('RECAPTCHA_FAIL_OPEN', false),

    /*
    |--------------------------------------------------------------------------
    | Skip reCAPTCHA for specific IPs
    |--------------------------------------------------------------------------
    |
    | IP addresses that should skip reCAPTCHA validation
    | Useful for testing and trusted sources
    |
    */
    'skip_ips' => [
        // '127.0.0.1',
        // '::1',
    ],
];