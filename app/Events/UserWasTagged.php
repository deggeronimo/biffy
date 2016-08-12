<?php namespace Biffy\Events;

use Biffy\Events\Event;

use Illuminate\Queue\SerializesModels;

class UserWasTagged extends Event {

	use SerializesModels;

    public $user;
    public $thread;
    public $post;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $thread
     * @param null $post
     * @return \Biffy\Events\UserWasTagged
     */
	public function __construct($user, $thread, $post = null)
	{
		$this->user = $user;
        $this->thread = $thread;
        $this->post = $post;
	}

}
