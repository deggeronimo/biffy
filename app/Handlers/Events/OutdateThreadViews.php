<?php namespace Biffy\Handlers\Events;

use Biffy\Events\ThreadHasNewPost;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class OutdateThreadViews implements ShouldBeQueued {
    use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  ThreadHasNewPost  $event
	 * @return void
	 */
	public function handle(ThreadHasNewPost $event)
	{
		// outdate all views except user's
        $views = $event->thread->thread_views;
        foreach ($views as $view) {
            if ($view->pivot->user_id !== $event->userId) {
                $view->pivot->current = 0;
                $view->pivot->save();
            }
        }
	}

}
