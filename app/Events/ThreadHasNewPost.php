<?php namespace Biffy\Events;

use Biffy\Events\Event;

use Illuminate\Queue\SerializesModels;

class ThreadHasNewPost extends Event {

	use SerializesModels;

    public $thread;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @param $thread
     * @param $userId
     * @return \Biffy\Events\ThreadHasNewPost
     */
	public function __construct($thread, $userId)
	{
		$this->thread = $thread;
        $this->userId = $userId;
	}

}
