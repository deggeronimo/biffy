<?php namespace Biffy\Handlers\Events;

use Biffy\Events\ThreadHasNewPost;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class NotifyThreadSubscribers implements ShouldBeQueued {

	use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  ThreadHasNewPost  $event
	 * @return void
	 */
	public function handle(ThreadHasNewPost $event)
	{
        $subs = $event->thread->subscriptions;
		foreach ($subs as $sub) {
            if ($sub->pivot->notify) {
                // todo do notification
                $sub->pivot->notify = false;
                $sub->pivot->save();
            }
        }
	}

}
