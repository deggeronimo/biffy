<?php

use Biffy\Services\Entities\ChecklistItem\ChecklistItemService;

class ChecklistItemsTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 1, 'name' => 'Charge Cord' ],
        [ 'id' => 2, 'name' => 'SIM Card' ]
    ];

    public function __construct(ChecklistItemService $service)
    {
        $this->service = $service;
    }
}