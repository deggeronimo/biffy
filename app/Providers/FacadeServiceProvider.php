<?php

namespace Biffy\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function provides()
    {
        return [
            'access',
            'directory',
            'time',
            'store-config',
            'command',
            'language',
            'biffy-cache'
        ];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['access'] = $this->app->share(
            function ($app) {
                /** @var Application $app */
                return $app->make('Biffy\Services\Access\AccessService');
            }
        );

        $this->app['directory'] = $this->app->share(
            function ($app) {
                return $app->make('Biffy\Services\Directory\DirectoryService');
            }
        );

        $this->app['time'] = $this->app->share(
            function ($app) {
                return $app->make('Biffy\Services\Time\TimeService');
            }
        );

        $this->app['store-config'] = $this->app->share(
            function ($app) {
                /** @var Application $app */
                return $app->make('Biffy\Services\Config\StoreConfigService');
            }
        );

        $this->app['command'] = $this->app->share(
            function ($app) {
                return $app->make('Biffy\Commands\CommandBus');
            }
        );

        $this->app['language'] = $this->app->share(
            function ($app) {
                return $app->make('Biffy\Services\Language\LanguageTranslatorService');
            }
        );

        $this->app['biffy-cache'] = $this->app->share(
            function ($app) {
                return $app->make('Biffy\Services\Cache\CacheService');
            }
        );
    }
}