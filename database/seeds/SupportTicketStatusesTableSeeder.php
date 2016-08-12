<?php

use Biffy\Entities\SupportTicketStatus\SupportTicketStatus;

class SupportTicketStatusesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'open' ],
        [ 'name' => 'close' ]
    ];

    public function __construct(SupportTicketStatus $model)
    {
        $this->model = $model;
    }
}