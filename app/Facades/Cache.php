<?php namespace Biffy\Facades;

use Illuminate\Support\Facades\Facade;

/** @see \Biffy\Services\Cache\CacheService */
class Cache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'biffy-cache';
    }
} 