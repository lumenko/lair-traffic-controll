<?php

declare(strict_types=1);

namespace LairTrafficControl\LairTrafficControl;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use LairTrafficControl\LairTrafficControl\Console\Commands\LairTrafficControlCommand;

class LairTrafficControlServiceProvider extends ServiceProvider
{
    protected static bool $isHandlingQuery = true;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lair-traffic-control.php', 'lair-traffic-control');

        $this->app->singleton(LairTrafficControl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'lair-traffic-control');

        if (! $this->app->runningInConsole()) {
            $this->commands([
                LairTrafficControlCommand::class,
            ]);
        }

        DB::listen(function ($query) {
            $cache = Cache::store(config('lair-traffic-control.default'));

            if (static::$isHandlingQuery) {
                return;
            }

            try {
                static::$isHandlingQuery = true;
                $cache->increment('traffic_total_db_queries');
                $cache->increment('traffic_total_db_time_ms', (int) round($query->time));

                if ($query->time > 50) {
                    $cache->increment('traffic_slow_db_queries');
                }
            } finally {
                static::$isHandlingQuery = false;
            }
        });

        $this->publishesMigrations([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ]);

        $this->publishes([
            __DIR__.'/../config/lair-traffic-control.php' => config_path('lair-traffic-control.php'),
        ]);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/lair-traffic-control'),
        ], ['lair-traffic-control', 'lair-traffic-control-lang']);

        $this->commands([
            LairTrafficControlCommand::class,
        ]);
    }
}
