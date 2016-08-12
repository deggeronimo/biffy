<?php

namespace Biffy\Facades;

use Illuminate\Support\Facades\Facade;

/** @see Biffy\Services\Config\StoreConfigService */

class StoreConfig extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'store-config';
    }
} 