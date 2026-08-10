<?php

declare(strict_types=1);

namespace LairTrafficControl\LairTrafficControl\Console\Commands;

use Illuminate\Console\Command;

class LairTrafficControlCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'lair-traffic-controll:placeholder';

    /**
     * The command description.
     */
    protected $description = 'Placeholder Artisan command shipped by the package lair-traffic-controll.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('LairTrafficControl placeholder command executed.');

        return self::SUCCESS;
    }
}
