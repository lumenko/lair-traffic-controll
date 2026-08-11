<?php

declare(strict_types=1);

namespace LairTrafficControl\LairTrafficControl\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class LairTrafficControlCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'lair-traffic-control:monitor {--clear : Clear history}';

    /**
     * The command description.
     */
    protected $description = 'Show HTTP and DB traffic in real time.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('clear')) {
            $this->resetStats();
            $this->info('History cleared.');

            return 1;
        }

        $cache = Cache::store(config('lair-traffic-control.default'));

        while (true) {
            $totalRequests = (int) $cache->get('traffic_total_requests', 0);
            $totalErrors = (int) $cache->get('traffic_total_errors', 0);
            $total404s = (int) $cache->get('traffic_total_404s', 0);
            $dbQueries = (int) $cache->get('traffic_total_db_queries', 0);
            $slowQueries = (int) $cache->get('traffic_slow_db_queries', 0);
            $routes = $cache->get('route', 'none');

            if (str_contains(PHP_OS, 'WIN')) {
                system('cls');
            } else {
                system('clear');
            }

            $this->line('<fg=cyan>===========================================================</>');
            $this->line('<fg=yellow;options=bold>   🚦 LAIR TRAFFIC CONTROL - LIVE MONITOR   </>');
            $this->line('<fg=cyan>===========================================================</>');

            $this->table(
                ['Metrics', 'Value'],
                [
                    ['Total Requests', $totalRequests],
                    ['Errors (5xx)', $totalErrors > 0 ? "<fg=red>{$totalErrors}</>" : '0'],
                    ['Not Found (404)', $total404s],
                    ['Total DB Queries', $dbQueries],
                    ['Slow DB Queries (>50ms)', $slowQueries > 0 ? "<fg=red>{$slowQueries}</>" : '0'],
                    ['Routes', $routes],
                ],
            );

            $this->comment('Last refresh: '.now()->format('H:i:s'));
            sleep(1);
        }
    }

    private function resetStats(): void
    {
        $cache = Cache::store(config('lair-traffic-control.default'));
        $cache->forget('traffic_total_requests');
        $cache->forget('traffic_total_errors');
        $cache->forget('traffic_total_404s');
        $cache->forget('traffic_total_db_queries');
        $cache->forget('traffic_slow_db_queries');
        $cache->forget('traffic_last_latency');
        $cache->forget('route');
    }
}
