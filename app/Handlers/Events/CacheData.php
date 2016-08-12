<?php namespace Biffy\Handlers\Events;

use Biffy\Events\CacheNeeded;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class CacheData implements ShouldBeQueued {
    use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  CacheNeeded  $event
	 * @return void
	 */
	public function handle(CacheNeeded $event)
	{
		$service = app("Biffy\\Services\\Entities\\{$event->type}\\{$event->type}Service");
        call_user_func_array([$service, 'cacheData'], $event->argArr);
	}

}
