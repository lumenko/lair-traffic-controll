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
        $this->mergeConfigFrom(__DIR__.'/../config/lair-traffic-controll.php', 'lair-traffic-controll');

        $this->app->singleton(LairTrafficControl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/lair-traffic-controll.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lair-traffic-controll');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'lair-traffic-controll');

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/lair-traffic-controll.php' => config_path('lair-traffic-controll.php'),
        ], ['lair-traffic-controll', 'lair-traffic-controll-config']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/lair-traffic-controll'),
        ], ['lair-traffic-controll', 'lair-traffic-controll-views']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/lair-traffic-controll'),
        ], ['lair-traffic-controll', 'lair-traffic-controll-lang']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/lair-traffic-controll'),
        ], ['lair-traffic-controll', 'lair-traffic-controll-assets']);

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['lair-traffic-controll', 'lair-traffic-controll-migrations']);

        $this->commands([
            LairTrafficControlCommand::class,
        ]);
    }
}
