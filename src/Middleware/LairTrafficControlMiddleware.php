<?php

namespace LairTrafficControl\LairTrafficControl\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LairTrafficControlMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $cache = Cache::store(config('lair-traffic-control.default'));
        $startTime = microtime(true);
        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $cache->increment('traffic_total_requests');
        $statusCode = $response->getStatusCode();
        $route = $request->getPathInfo();

        $cache->add('route', $route);
        if ($statusCode >= 500) {
            $cache->increment('traffic_total_errors');
        } elseif ($statusCode === 400) {
            $cache->increment('traffic_total_404s');
        }

        $cache->put('traffic_last_latency', $duration);

        return $response;
    }
}
