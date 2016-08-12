<?php namespace Biffy\Events;

use Illuminate\Queue\SerializesModels;

class ThreadViewed extends Event {

	use SerializesModels;

    public $thread;

    public $userId;

    /**
     * Create a new event instance.
     *
     * @param $thread
     * @param $userId
     * @return \Biffy\Events\ThreadViewed
     */
	public function __construct($thread, $userId)
	{
		$this->thread = $thread;
        $this->userId = $userId;
	}

}
