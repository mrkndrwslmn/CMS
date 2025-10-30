<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Configuration
    |--------------------------------------------------------------------------
    |
    | Firebase Cloud Messaging (FCM) configuration for push notifications
    | and real-time messaging features.
    |
    */

    'api_key' => env('FIREBASE_API_KEY'),
    'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
    'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
    'app_id' => env('FIREBASE_APP_ID'),
    'measurement_id' => env('FIREBASE_MEASUREMENT_ID'),
    
    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging (FCM) Server Key
    |--------------------------------------------------------------------------
    |
    | This key is used for server-side push notifications via FCM API
    |
    */
    'fcm_server_key' => env('FIREBASE_FCM_SERVER_KEY'),
    
    /*
    |--------------------------------------------------------------------------
    | Firebase Service Account
    |--------------------------------------------------------------------------
    |
    | Path to Firebase service account JSON file for admin SDK
    |
    */
    'service_account_path' => env('FIREBASE_SERVICE_ACCOUNT_PATH', storage_path('app/message/firebase-cms.json')),
    
    /*
    |--------------------------------------------------------------------------
    | VAPID Key for Web Push
    |--------------------------------------------------------------------------
    |
    | Voluntary Application Server Identification (VAPID) key for web push
    |
    */
    'vapidKey' => env('FIREBASE_VAPID_KEY'),
    
    /*
    |--------------------------------------------------------------------------
    | Firebase Database URL
    |--------------------------------------------------------------------------
    |
    | Your Firebase Realtime Database URL
    |
    */
    'database_url' => env('FIREBASE_DATABASE_URL'),
    
    /*
    |--------------------------------------------------------------------------
    | FCM API Endpoint
    |--------------------------------------------------------------------------
    |
    | Firebase Cloud Messaging API endpoint
    |
    */
    'fcm_endpoint' => 'https://fcm.googleapis.com/fcm/send',
    
    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Default notification settings
    |
    */
    'notification_icon' => env('APP_URL') . '/favicon.ico',
    'notification_sound' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Firebase Authentication
    |--------------------------------------------------------------------------
    |
    | Firebase Authentication configuration for social login
    | Get the Web API Key from Firebase Console -> Project Settings -> General
    |
    */
    'authentication' => [
        'enabled' => env('FIREBASE_AUTH_ENABLED', true),
        'web_api_key' => env('FIREBASE_WEB_API_KEY', env('FIREBASE_API_KEY')),
        'social_providers' => [
            'google' => env('FIREBASE_GOOGLE_ENABLED', true),
            'apple' => env('FIREBASE_APPLE_ENABLED', false),
            'twitter' => env('FIREBASE_TWITTER_ENABLED', true),
        ],
    ],

    // Legacy keys for backward compatibility
    'web_api_key' => env('FIREBASE_WEB_API_KEY', env('FIREBASE_API_KEY')),
    'social_providers' => [
        'google' => env('FIREBASE_GOOGLE_ENABLED', true),
        'apple' => env('FIREBASE_APPLE_ENABLED', false),
        'twitter' => env('FIREBASE_TWITTER_ENABLED', true),
    ],

];
