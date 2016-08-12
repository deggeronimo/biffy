<?php namespace Biffy\Facades;

use Illuminate\Support\Facades\Facade;

class LanguageTranslator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'language';
    }
}
