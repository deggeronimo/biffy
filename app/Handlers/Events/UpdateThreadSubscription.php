<?php namespace Biffy\Handlers\Events;

use Biffy\Events\ThreadViewed;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class UpdateThreadSubscription implements ShouldBeQueued {
    use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  ThreadViewed  $event
	 * @return void
	 */
	public function handle(ThreadViewed $event)
	{
		$subs = $event->thread->subscriptions;
        foreach ($subs as $sub) {
            if ($sub->pivot->user_id === $event->userId) {
                $sub->pivot->notify = true;
                $sub->pivot->save();
            }
        }
	}

}
