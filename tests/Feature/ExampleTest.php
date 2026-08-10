<?php

declare(strict_types=1);

use LairTrafficControl\LairTrafficControl\LairTrafficControl;

it('resolves the singleton', function () {
    expect(app(LairTrafficControl::class))->toBeInstanceOf(LairTrafficControl::class);
});

it('returns the same instance from the container', function () {
    expect(app(LairTrafficControl::class))->toBe(app(LairTrafficControl::class));
});

it('merges the package config', function () {
    expect(config('lair-traffic-controll.placeholder'))->toBe('default');
});

it('loads the package translations', function () {
    expect(trans('lair-traffic-controll::messages.placeholder'))->toBe('LairTrafficControl placeholder translation.');
});

it('loads the package views', function () {
    expect(view()->exists('lair-traffic-controll::placeholder'))->toBeTrue();
});

it('registers the artisan command', function () {
    $this->artisan('lair-traffic-controll:placeholder')
        ->expectsOutputToContain('LairTrafficControl placeholder command executed.')
        ->assertSuccessful();
});
