<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the cache connection that will be used to store
    | traffic and database metrics. For production, 'redis' is highly
    | recommended. Avoid 'database' or 'file' if traffic is heavy.
    |
    */
    'default' => env('LAIR_TRAFFIC_STORE', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Performance Tracking Switches
    |--------------------------------------------------------------------------
    |
    | Master switches to easily enable or disable metrics gathering without
    | having to remove the middleware or service providers manually.
    |
    */
    'enabled' => env('LAIR_TRAFFIC_ENABLED', true),

    'track_queries' => env('LAIR_TRAFFIC_TRACK_QUERIES', false),

    /*
    |--------------------------------------------------------------------------
    | Metric Definitions & Thresholds
    |--------------------------------------------------------------------------
    |
    | Define custom parameters for what qualifies as a metric flag.
    |
    | slow_query_ms: Queries taking longer than this will increment the slow count.
    | cache_prefix: Prefixed to cache keys to prevent collision with app cache.
    |
    */
    'slow_query_ms' => env('LAIR_TRAFFIC_SLOW_QUERY_MS', 50),

    'cache_prefix' => 'lair_traffic_',

];
