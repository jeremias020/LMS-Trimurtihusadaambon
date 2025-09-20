<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Execution Time Configuration
    |--------------------------------------------------------------------------
    |
    | Configure maximum execution time and memory limits for different
    | operations in your Laravel application.
    |
    */

    'max_execution_time' => env('MAX_EXECUTION_TIME', 300), // 5 minutes
    'memory_limit' => env('MEMORY_LIMIT', '512M'),
    'db_timeout' => env('DB_TIMEOUT', 60),
    
    /*
    |--------------------------------------------------------------------------
    | Operation-Specific Timeouts
    |--------------------------------------------------------------------------
    |
    | Set specific timeouts for different operations
    |
    */
    
    'operations' => [
        'import' => 600,      // 10 minutes for data imports
        'export' => 300,      // 5 minutes for exports
        'backup' => 900,      // 15 minutes for backups
        'migration' => 300,   // 5 minutes for migrations
        'seed' => 180,        // 3 minutes for seeding
    ],
];
