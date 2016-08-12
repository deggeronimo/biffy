<?php

use Biffy\Services\Entities\DeviceRepairOptionItem\DeviceRepairOptionItemService;

class DeviceRepairOptionItemsTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'device_repair_id' => 643, 'device_repair_option_id' => 1, 'item_id' => 992, 'option_value' => 'Red', 'image' => '' ],
        [ 'device_repair_id' => 643, 'device_repair_option_id' => 1, 'item_id' => 1092, 'option_value' => 'Black', 'image' => '' ],
        [ 'device_repair_id' => 643, 'device_repair_option_id' => 1, 'item_id' => 1093, 'option_value' => 'White', 'image' => '' ],
        [ 'device_repair_id' => 643, 'device_repair_option_id' => 1, 'item_id' => 1644, 'option_value' => 'Blue', 'image' => '' ],
        [ 'device_repair_id' => 643, 'device_repair_option_id' => 1, 'item_id' => 1645, 'option_value' => 'Purple', 'image' => '' ],
        [ 'device_repair_id' => 643, 'device_repair_option_id' => 1, 'item_id' => 1646, 'option_value' => 'Brown', 'image' => '' ],
        [ 'device_repair_id' => 643, 'device_repair_option_id' => 1, 'item_id' => 1647, 'option_value' => 'Pink', 'image' => '' ],
        [ 'device_repair_id' => 643, 'device_repair_option_id' => 1, 'item_id' => 2806, 'option_value' => 'Other', 'image' => '' ]
    ];

    /**
     * @param DeviceRepairOptionItemService $service
     */
    public function __construct(DeviceRepairOptionItemService $service)
    {
        $this->service = $service;
    }
}