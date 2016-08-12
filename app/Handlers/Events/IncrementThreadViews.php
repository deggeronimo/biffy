<?php namespace Biffy\Handlers\Events;

use Biffy\Events\ThreadViewed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class IncrementThreadViews implements ShouldBeQueued {

	use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  ThreadViewed  $event
	 * @return void
	 */
	public function handle(ThreadViewed $event)
	{
		$thread = $event->thread;
        $thread->views++;
        $thread->save();
	}

}
