<?php namespace Biffy\Handlers\Events;

use Biffy\Events\ThreadViewed;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class MakeThreadViewCurrent implements ShouldBeQueued {
    use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  ThreadViewed  $event
	 * @return void
	 */
	public function handle(ThreadViewed $event)
	{
        $views = $event->thread->thread_views;
        foreach ($views as $view) {
            if ($view->id === $event->userId) {
                $view->pivot->current = true;
                $view->pivot->save();
                return;
            }
        }

        $event->thread->thread_views()->attach($event->userId, ['current' => true]);
	}

}
