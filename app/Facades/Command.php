<?php

namespace Biffy\Facades;

use Illuminate\Support\Facades\Facade;

/** @see Biffy\Commands\CommandBus */

class Command extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'command';
    }
} 