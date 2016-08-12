<?php

use Biffy\Services\Entities\Item\ItemService;
use Illuminate\Database\Seeder;

class ItemsTableSeeder2 extends Seeder
{
    protected $itemList = [
        [ 'id' => 983, 'item_number' => 14076, 'unit_price' => 199.99, 'labor_cost' => 50.00, 'distro_price' => 85.00,
            'name' => 'Samsung Galaxy S4 Glass/LCD, All Carriers, Black Mist', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 1049, 'required' => 0 ],
        [ 'id' => 992, 'item_number' => 14078, 'unit_price' => 129.99, 'labor_cost' => 0.00, 'distro_price' => 5.00,
            'name' => 'Samsung Galaxy S4 Glass Only, All Carriers, Red', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 1059, 'required' => 0 ],
        [ 'id' => 1000, 'item_number' => 14081, 'unit_price' => 49.99, 'labor_cost' => 0.00, 'distro_price' => 5.00,
            'name' => 'Samsung Galaxy S4 SIM/SD Card Flex', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 1067, 'required' => 0 ],
        [ 'id' => 1092, 'item_number' => 14083, 'unit_price' => 129.99, 'labor_cost' => 0.00, 'distro_price' => 5.00,
            'name' => 'Samsung Galaxy S4 Glass Only, All Carriers, Black', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 1167, 'required' => 0 ],
        [ 'id' => 1093, 'item_number' => 14084, 'unit_price' => 129.99, 'labor_cost' => 0.00, 'distro_price' => 5.00,
            'name' => 'Samsung Galaxy S4 Glass Only, All Carriers, White', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 1168, 'required' => 0 ],
        [ 'id' => 1644, 'item_number' => 14194, 'unit_price' => 129.99, 'labor_cost' => 0.00, 'distro_price' => 5.00,
            'name' => 'Samsung Galaxy S4 Glass Only, All Carriers, Blue', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 1761, 'required' => 0 ],
        [ 'id' => 1645, 'item_number' => 14195, 'unit_price' => 129.99, 'labor_cost' => 0.00, 'distro_price' => 5.00,
            'name' => 'Samsung Galaxy S4 Glass Only, All Carriers, Purple', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 1762, 'required' => 0 ],
        [ 'id' => 1646, 'item_number' => 14196, 'unit_price' => 129.99, 'labor_cost' => 0.00, 'distro_price' => 5.00,
            'name' => 'Samsung Galaxy S4 Glass Only, All Carriers, Brown', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 1763, 'required' => 0 ],
        [ 'id' => 1647, 'item_number' => 14197, 'unit_price' => 129.99, 'labor_cost' => 0.00, 'distro_price' => 5.00,
            'name' => 'Samsung Galaxy S4 Glass Only, All Carriers, Pink', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 1764, 'required' => 0 ],
        [ 'id' => 2168, 'item_number' => 12029, 'unit_price' => 69.99, 'labor_cost' => 0.00, 'distro_price' => 0.00,
            'name' => 'Samsung Galaxy Power Button', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
            'device_type_id' => 235, 'old_item_id' => 2316, 'required' => 0 ],
        [ 'id' => 2806, 'item_number' => 14303, 'unit_price' => 129.99, 'labor_cost' => 0.00, 'distro_price' => 5.00,
        'name' => 'Samsung Galaxy S4 Glass Only, All Carriers, Other', 'global' => 1, 'item_type_id' => 1, 'vendor_id' => 1,
        'device_type_id' => 235, 'old_item_id' => 2971, 'required' => 0 ]
    ];

    public function __construct(ItemService $service)
    {
        $this->service = $service;
    }

    public function run()
    {
        foreach ($this->itemList as $item) {
            $this->service->create($item);
        }
    }
}