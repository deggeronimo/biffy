<?php

use Biffy\Entities\FeedbackStatus\FeedbackStatus;

class FeedbackStatusesSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'Reported' ],
        [ 'name' => 'Customer Contacted' ],
        [ 'name' => 'Awaiting Response' ],
        [ 'name' => 'Resolved' ],
        [ 'name' => 'Response Needed' ],
        [ 'name' => 'Store Contacted' ],
        [ 'name' => 'Other' ]
    ];

    public function __construct(FeedbackStatus $model)
    {
        $this->model = $model;
    }
}

