<?php

if (!is_null(env('CODECEPT')) && array_key_exists('HTTP_X_CODECEPT', $_SERVER) && array_key_exists('HTTP_X_CODECEPT_OPTS', $_SERVER) && $_SERVER['HTTP_X_CODECEPT'] === env('CODECEPT')) {
    $opts = explode(';', $_SERVER['HTTP_X_CODECEPT_OPTS']);
    foreach ($opts as $opt) {
        putenv($opt);
    }
}

if (!class_exists('BiffyApplication')) {
    class BiffyApplication extends Illuminate\Foundation\Application
    {
        public function publicPath()
        {
            if ( env('APP_ENV') !== 'production' && ((isset($_SERVER['SERVER_NAME']) ? preg_match('/localhost$|homestead.app|portal.ubif.net$/', $_SERVER['SERVER_NAME']) : true)
                    || getenv('APP_ENV') === 'local' || php_sapi_name() === 'cli')) {
                $path = 'theme';
            } else {
                $path = 'public';
            }

            return $this->basePath . '/' . $path;
        }

        protected function registerBaseServiceProviders()
        {
            $this->register(new Illuminate\Events\EventServiceProvider($this));

            $this->register(new Biffy\Routing\RoutingServiceProvider($this));
        }
    }
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new BiffyApplication(
    realpath(__DIR__.'/..')
);

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app->singleton(
    'Illuminate\Contracts\Debug\ExceptionHandler',
    'Biffy\Exceptions\Handler'
);

$app->singleton(
    'Illuminate\Contracts\Http\Kernel',
    'Biffy\Http\Kernel'
);

$app->singleton(
    'Illuminate\Contracts\Console\Kernel',
    'Biffy\Console\Kernel'
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;