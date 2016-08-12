<?php

namespace Biffy\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogHandler;
use Illuminate\Contracts\Logging\Log;

class LogServiceProvider extends ServiceProvider
{

    public function boot(Log $log)
    {
        $monolog = $this->app['log']->getMonolog();
        $syslog = new SyslogHandler('papertrail');
        $formatter = new LineFormatter('%channel%.%level_name%: %message% %extra%');
        $syslog->setFormatter($formatter);
        $monolog->pushHandler($syslog);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}