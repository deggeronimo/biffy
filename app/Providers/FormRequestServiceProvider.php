<?php

namespace Biffy\Providers;

use Illuminate\Support\ServiceProvider;

class FormRequestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bindShared('Biffy\Http\Requests\AbstractFormRequest', function ($app) {
                $routeParts = explode('@', $app['router']->currentRouteAction());
                $routeAction = end($routeParts);

                $classParts = explode('\\', reset($routeParts));
                $className = end($classParts);
                $modelName = str_replace('Controller', '', $className);

                $formRequestVerb = '';
                if ($routeAction == 'store') {
                    $formRequestVerb = 'Create';
                } else if ($routeAction == 'update') {
                    $formRequestVerb = 'Update';
                }

                $formRequestName = $formRequestVerb . $modelName . 'Request';

                return $app->make('Biffy\Http\Requests\\' . $modelName . '\\' . $formRequestName);
            });
    }
}