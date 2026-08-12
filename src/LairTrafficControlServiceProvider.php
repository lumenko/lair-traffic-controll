<?php

declare(strict_types=1);

namespace LairTrafficControl\LairTrafficControl;

use Illuminate\Support\ServiceProvider;
use LairTrafficControl\LairTrafficControl\Console\Commands\LairTrafficControlCommand;

class LairTrafficControlServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lair-traffic-control.php', 'lair-traffic-control');

        config([
            'cache.stores.traffic_store' => [
                'driver' => 'database',
                'table' => 'lair_traffic_control_monitor',
                'connection' => null,
                'lock_connection' => null,
            ],
        ]);

        $this->app->singleton(LairTrafficControl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'lair-traffic-control');

        if ($this->app->runningInConsole()) {
            $this->commands([
                LairTrafficControlCommand::class,
            ]);

            $this->publishesMigrations([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ]);

            $this->publishes([
                __DIR__.'/../config/lair-traffic-control.php' => config_path('lair-traffic-control.php'),
            ]);

            $this->publishes([
                __DIR__.'/../lang' => $this->app->langPath('vendor/lair-traffic-control'),
            ], ['lair-traffic-control', 'lair-traffic-control-lang']);
        }
    }
}
