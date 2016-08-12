<?php

use Biffy\Entities\DeviceRepair\DeviceRepair;
use Biffy\Services\Entities\DeviceRepair\DeviceRepairService;

class DeviceRepairsTableSeeder2 extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 633, 'device_repair_type_id' => 1, 'name' => 'Samsung Galaxy S4 Diagnostic Repair', 'device_type_id' => 235,
            'image' => 'data/repairs/samsung/galaxy-s4/galaxy-s4-water-damager-repair.jpg', 'status' => 1 ],
        [ 'id' => 634, 'device_repair_type_id' => 1, 'name' => 'Samsung Galaxy S4 Galss & LCD Replacement Repair', 'device_type_id' => 235, 'item_id' => 983,
            'image' => 'data/repairs/samsung/galaxy-s4/galaxy-s4-glass-lcd-repair.jpg', 'status' => 1 ],
        [ 'id' => 643, 'device_repair_type_id' => 1, 'name' => 'Samsung Galaxy S4 Glass Replacement Repair', 'device_type_id' => 235, 'item_id' => 1000,
            'image' => 'data/repairs/samsung/galaxy-s4/galaxy-s4-glass-repair.jpg', 'status' => 1 ],
        [ 'id' => 807, 'device_repair_type_id' => 1, 'name' => 'Samsung Galaxy S4 Water Damage Repair Diagnostic Repair', 'device_type_id' => 235,
            'image' => 'data/repairs/samsung/galaxy-s4/galaxy-s4-water-damager-repair.jpg', 'status' => 1 ]
    ];

    protected $stringList = [
        [
            'id' => 633,
            'values' => [
                'name' => [
                    'en' => 'Samsung Galaxy S4 Diagnostic Repair',
                    'ca_fr' => '', 'ca' => '', 'tt' => ''
                ]
            ]
        ],
        [
            'id' => 634,
            'values' => [
                'name' => [
                    'en' => 'Samsung Galaxy S4 Galss & LCD Replacement Repair',
                    'ca_fr' => '', 'ca' => '', 'tt' => ''
                ]
            ]
        ],
        [
            'id' => 643,
            'values' => [
                'name' => [
                    'en' => 'Samsung Galaxy S4 Glass Replacement Repair',
                    'ca_fr' => '', 'ca' => '', 'tt' => ''
                ]
            ]
        ],
        [
        'id' => 807,
        'values' => [
            'name' => [
                'en' => 'Samsung Galaxy S4 Water Damage Repair Diagnostic Repair',
                'ca_fr' => '', 'ca' => '', 'tt' => ''
            ]
        ]
    ]
    ];

    public function __construct(DeviceRepairService $service, DeviceRepair $model)
    {
        $this->service = $service;
        $this->model = $model;
    }
}