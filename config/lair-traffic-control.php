<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store for Lair Traffic Controll
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache store that will be used by the
    | framework. This connection is utilized if another isn't explicitly
    | specified when running a cache operation inside the application.
    |
    */

    'default' => env('LAIR_CACHE_STORE', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    | Supported drivers: "database", "file", "redis"
    |
    |
    */

    'stores' => [

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_LAIR_CONNECTION', env('DB_CONNECTION', 'mysql')),
            'table' => env('DB_LAIR_TABLE', 'lair_traffic_control_monitor'),
            'lock_connection' => env('DB_LAIR_LOCK_CONNECTION', env('DB_CONNECTION', 'mysql')),
            'lock_table' => env('DB_LAIR_LOCK_TABLE', 'lair_traffic_control_monitor'),
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/lair/data'),
            'lock_path' => storage_path('framework/lair/data'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION', 'lair'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],


        'failover' => [
            'driver' => 'failover',
            'stores' => [
                'database',
                'array',
            ],
        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | Serializable Classes
    |--------------------------------------------------------------------------
    |
    | This value determines the classes that can be unserialized from cache
    | storage. By default, no PHP classes will be unserialized from your
    | cache to prevent gadget chain attacks if your APP_KEY is leaked.
    |
    */

    'serializable_classes' => false,

];
