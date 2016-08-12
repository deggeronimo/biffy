<?php

use Biffy\Services\Entities\DeviceRepairType\DeviceRepairTypeService;

class DeviceRepairTypesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 1, 'image_overlay' => '', 'class' => '', 'sort_order' => 0 ]
    ];

    public function __construct(DeviceRepairTypeService $service)
    {
        $this->service = $service;
    }
}