<?php

namespace Biffy\Facades;

use Illuminate\Support\Facades\Facade;

/** @see Biffy\Services\Access\AccessService */

class Access extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'access';
    }
} 