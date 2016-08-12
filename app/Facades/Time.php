<?php namespace Biffy\Facades;

use Illuminate\Support\Facades\Facade;

class Time extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'time';
    }
} 