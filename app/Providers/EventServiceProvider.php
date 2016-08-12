<?php

namespace Biffy\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Biffy\Events\ThreadViewed' => ['Biffy\Handlers\Events\IncrementThreadViews', 'Biffy\Handlers\Events\MakeThreadViewCurrent', 'Biffy\Handlers\Events\UpdateThreadSubscription'],
        'Biffy\Events\ThreadHasNewPost' => ['Biffy\Handlers\Events\NotifyThreadSubscribers', 'Biffy\Handlers\Events\OutdateThreadViews'],
        'Biffy\Events\UserWasTagged' => ['Biffy\Handlers\Events\NotifyTaggedUser'],
        'Biffy\Events\CacheNeeded' => ['Biffy\Handlers\Events\CacheData']
    ];

    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
    }

    public function register()
    {
        if (env('LOG_QUERIES', false)) {
            \Event::listen(
                'illuminate.query',
                function ($query, $bindings, $time) {
                    \Log::debug($query, [ $bindings, $time ]);
                }
            );
        }
    }
} 