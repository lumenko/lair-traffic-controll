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
    protected $signature = 'lair-traffic-control:monitor';

    /**
     * The command description.
     */
    protected $description = 'Show HTTP and DB traffic in real time.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        while (true) {
            $totalRequests = (int) Cache::get('traffic_total_requests', 0);
            $totalErrors = (int) Cache::get('traffic_total_errors', 0);
            $total404s = (int) Cache::get('traffic_total_404s', 0);
            $dbQueries = (int) Cache::get('traffic_total_db_queries', 0);
            $slowQueries = (int) Cache::get('traffic_slow_db_queries', 0);

            if (str_contains(PHP_OS, 'WIN')) {
                system('cls');
            } else {
                system('clear');
            }

            $this->line('<fg=cyan>============================================</>');
            $this->line('<fg=yellow;options=bold>   🚦 LAIR TRAFFIC CONTROL - LIVE MONITOR   </>');
            $this->line('<fg=cyan>============================================</>');

            $this->table(
                ['Metrics', 'Value'],
                [
                    ['Total Requests', $totalRequests],
                    ['Errors (5xx)', $totalErrors > 0 ? "<fg=red>{$totalErrors}</>" : '0'],
                    ['Not Found (404)', $total404s],
                    ['Total DB Queries', $dbQueries],
                    ['Slow DB Queries (>50ms)', $slowQueries > 0 ? "<fg=red>{$slowQueries}</>" : '0'],
                ],
            );

            $this->comment('Last refresh: '.now()->format('H:i:s'));
            sleep(1);
        }

        return Command::SUCCESS;
    }
}
