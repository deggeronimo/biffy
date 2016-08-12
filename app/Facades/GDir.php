<?php

namespace Biffy\Facades;

use Illuminate\Support\Facades\Facade;

/** @see Biffy\Services\Directory\DirectoryService */

class GDir extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'directory';
    }
} 