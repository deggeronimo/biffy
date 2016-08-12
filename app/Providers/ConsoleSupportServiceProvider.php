<?php namespace Biffy\Providers;

use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider as IlluminateConsoleSupportServiceProvider;

class ConsoleSupportServiceProvider extends IlluminateConsoleSupportServiceProvider
{
    public function __construct($application)
    {
        parent::__construct($application);

        $key = array_search('Illuminate\Database\MigrationServiceProvider', $this->providers);
        $this->providers[$key] = 'Biffy\Providers\MigrationServiceProvider';
    }
} 