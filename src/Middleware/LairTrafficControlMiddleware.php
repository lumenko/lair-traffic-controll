<?php

namespace LairTrafficControl\LairTrafficControl\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class LairTrafficControlMiddleware
{
    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request, Closure $next)
    {
        $cache = Cache::store('traffic_store');
        $startTime = microtime(true);
        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        if ($cache->has('traffic_total_requests')) {
            $cache->increment('traffic_total_requests');
        } else {
            $cache->put('traffic_total_requests', 1);
        }

        $statusCode = $response->getStatusCode();
        $route = $request->getPathInfo();

        $cache->put('route', $route);

        if ($statusCode >= 500) {
            if ($cache->has('traffic_total_errors')) {
                $cache->increment('traffic_total_errors');
            } else {
                $cache->put('traffic_total_errors', 1);
            }
        } elseif ($statusCode === 404) { // Fix 3: Typo correction (404, not 400)
            if ($cache->has('traffic_total_404s')) {
                $cache->increment('traffic_total_404s');
            } else {
                $cache->put('traffic_total_404s', 1);
            }
        }

        $cache->put('traffic_last_latency', $duration);

        return $response;
    }
}
