<?php namespace Biffy\Handlers\Events;

use Biffy\Events\UserWasTagged;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class NotifyTaggedUser implements ShouldBeQueued {
    use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  UserWasTagged  $event
	 * @return void
	 */
	public function handle(UserWasTagged $event)
	{
		// todo send email
	}

}
