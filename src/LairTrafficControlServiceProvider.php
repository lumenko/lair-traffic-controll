<?php

declare(strict_types=1);

namespace LairTrafficControl\LairTrafficControl;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

        $this->app->singleton(LairTrafficControl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/lair-traffic-control.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lair-traffic-control');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'lair-traffic-control');

        DB::listen(function ($query) {
            Cache::increment('traffic_total_db_queries');
            Cache::increment('traffic_total_db_time_ms', (int) round($query->time));
            if($query->time > 50) {
                Cache::increment('traffic_slow_db_queries');
            }
        });

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/lair-traffic-control.php' => config_path('lair-traffic-control.php'),
        ], ['lair-traffic-control', 'lair-traffic-control-config']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/lair-traffic-control'),
        ], ['lair-traffic-control', 'lair-traffic-control-views']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/lair-traffic-control'),
        ], ['lair-traffic-control', 'lair-traffic-control-lang']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/lair-traffic-control'),
        ], ['lair-traffic-control', 'lair-traffic-control-assets']);

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['lair-traffic-control', 'lair-traffic-control-migrations']);

        $this->commands([
            LairTrafficControlCommand::class,
        ]);
    }
}
