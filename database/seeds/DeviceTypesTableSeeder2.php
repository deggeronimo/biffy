<?php

use Biffy\Entities\DeviceType\DeviceType;
use Biffy\Services\Entities\DeviceType\DeviceTypeService;

class DeviceTypesTableSeeder2 extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 9, 'image' => 'data/repairs/samsung/note-3/note-3.jpg', 'top' => 1, 'sort_order' => 5,
            'product' => 0, 'status' => 1, 'model' => '', 'view_count' => 0, 'release_date' => '2015-04-01', 'filters' => '' ],
        [ 'id' => 73, 'parent_device_type_id' => 9, 'image' => 'data/repairs/samsung/note-3/note-3.jpg',
            'top' => 0, 'sort_order' => 0, 'product' => 0, 'status' => 1, 'model' => '', 'view_count' => 0, 'release_date' => '2015-04-01',
            'filters' => '' ],
        [ 'id' => 235, 'parent_device_type_id' => 73, 'device_type_filter_id' => 9, 'device_manufacturer' => 16,
            'device_family_id' => 25, 'image' => 'data/phones/samsung/gs4.jpg', 'top' => 0, 'sort_order' => 0, 'product' => 0,
            'status' => 1, 'model' => '', 'view_count' => 0, 'release_date' => '2015-04-01', 'filters' => '' ]
    ];

    protected $stringList = [
        [
            'id' => 9,
            'values' => [
                'name' => [
                    'en' => 'Smartphone Repair',
                    'ca_fr' => '', 'ca' => '', 'tt' => ''
                ]
            ]
        ],
        [
            'id' => 73,
            'values' => [
                'name' => [
                    'en' => 'Samsung Repair',
                    'ca_fr' => '', 'ca' => '', 'tt' => ''
                ]
            ]
        ],
        [
            'id' => 235,
            'values' => [
                'name' => [
                    'en' => 'Samsung Galaxy S4 Repair',
                    'ca_fr' => '', 'ca' => '', 'tt' => ''
                ]
            ]
        ]
    ];

    public function __construct(DeviceTypeService $service, DeviceType $model)
    {
        $this->service = $service;
        $this->model = $model;
    }
}