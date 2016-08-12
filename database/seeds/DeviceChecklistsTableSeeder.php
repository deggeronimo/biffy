<?php

use Biffy\Services\Entities\DeviceChecklist\DeviceChecklistService;

class DeviceChecklistsTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 1, 'device_type_id' => 235, 'checklist_function_id' => 2, 'item_id' => 983 ],
        [ 'id' => 2, 'device_type_id' => 235, 'checklist_function_id' => 3, 'item_id' => 2168 ],
        [ 'id' => 3, 'device_type_id' => 235, 'checklist_function_id' => 1, 'item_id' => 992 ],
    ];

    public function __construct(DeviceChecklistService $service)
    {
        $this->service = $service;
    }
}