<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Azure Database Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options specific to Azure Database for MySQL
    |
    */

    'database' => [
        /*
        |--------------------------------------------------------------------------
        | Azure Database for MySQL Settings
        |--------------------------------------------------------------------------
        */
        'mysql' => [
            'connection_timeout' => env('AZURE_DB_CONNECTION_TIMEOUT', 30),
            'read_timeout' => env('AZURE_DB_READ_TIMEOUT', 30),
            'write_timeout' => env('AZURE_DB_WRITE_TIMEOUT', 30),
            
            // SSL Configuration
            'ssl' => [
                'enabled' => env('AZURE_MYSQL_SSL_ENABLED', true),
                'ca_path' => env('AZURE_MYSQL_SSL_CA', storage_path('certificates/BaltimoreCyberTrustRoot.crt.pem')),
                'verify_server_cert' => env('AZURE_MYSQL_SSL_VERIFY', true),
                'cipher' => env('AZURE_MYSQL_SSL_CIPHER', ''),
            ],
            
            // Connection Pool Settings
            'pool' => [
                'max_connections' => env('AZURE_DB_MAX_CONNECTIONS', 10),
                'idle_timeout' => env('AZURE_DB_IDLE_TIMEOUT', 600),
                'wait_timeout' => env('AZURE_DB_WAIT_TIMEOUT', 28800),
            ],
            
            // Performance Settings
            'performance' => [
                'query_cache' => env('AZURE_DB_QUERY_CACHE', true),
                'persistent_connections' => env('AZURE_DB_PERSISTENT', false),
            ],
        ],
        
        /*
        |--------------------------------------------------------------------------
        | Migration Settings
        |--------------------------------------------------------------------------
        */
        'migration' => [
            'chunk_size' => env('AZURE_MIGRATION_CHUNK_SIZE', 1000),
            'timeout' => env('AZURE_MIGRATION_TIMEOUT', 300),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Azure Resource Configuration
    |--------------------------------------------------------------------------
    */
    'resource' => [
        'subscription_id' => env('AZURE_SUBSCRIPTION_ID'),
        'resource_group' => env('AZURE_RESOURCE_GROUP'),
        'location' => env('AZURE_LOCATION', 'East US'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring & Logging
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'connection_logging' => env('AZURE_DB_CONNECTION_LOGGING', false),
        'slow_query_logging' => env('AZURE_DB_SLOW_QUERY_LOGGING', true),
        'slow_query_threshold' => env('AZURE_DB_SLOW_QUERY_THRESHOLD', 2), // seconds
    ],
];