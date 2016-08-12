<?php namespace Biffy\Events;

use Biffy\Events\Event;

use Illuminate\Queue\SerializesModels;

class CacheNeeded extends Event {

	use SerializesModels;

    public $type;
    public $argArr = [];

    /**
     * Create a new event instance.
     *
     * @param $data
     * @return \Biffy\Events\CacheNeeded
     */
	public function __construct($data)
	{
		$this->type = $data['type'];

        if (array_key_exists('storeId', $data)) {
            $this->argArr[] = $data['storeId'];
        }
        if (array_key_exists('userId', $data)) {
            $this->argArr[] = $data['userId'];
        }
	}

}
