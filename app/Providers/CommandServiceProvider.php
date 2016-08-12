<?php

namespace Biffy\Providers;

use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Biffy\Commands\CommandTranslator', 'Biffy\Commands\BasicCommandTranslator');

        $this->app->bind('Biffy\Commands\CommandBus', function ($app) {
                return $app->make('Biffy\Commands\DefaultCommandBus');
            });
    }
}