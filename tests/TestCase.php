<?php

declare(strict_types=1);

namespace LairTrafficControl\LairTrafficControl\Tests;

use LairTrafficControl\LairTrafficControl\LairTrafficControlServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LairTrafficControlServiceProvider::class,
        ];
    }
}
