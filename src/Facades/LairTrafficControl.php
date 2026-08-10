<?php

declare(strict_types=1);

namespace LairTrafficControl\LairTrafficControl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LairTrafficControl\LairTrafficControl\LairTrafficControl
 */
class LairTrafficControl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LairTrafficControl\LairTrafficControl\LairTrafficControl::class;
    }
}
