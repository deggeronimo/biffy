<?php

use Biffy\Services\Entities\DeviceItemChecklist\DeviceItemChecklistService;

class DeviceItemChecklistsTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 1, 'device_type_id' => 235, 'checklist_item_id' => 1 ],
        [ 'id' => 2, 'device_type_id' => 235, 'checklist_item_id' => 2 ]
    ];

    public function __construct(DeviceItemChecklistService $service)
    {
        $this->service = $service;
    }
}