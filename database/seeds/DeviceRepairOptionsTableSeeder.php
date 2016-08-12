<?php

use Biffy\Entities\DeviceRepairOption\DeviceRepairOption;

class DeviceRepairOptionsTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 1, 'name' => 'Color' ]
    ];

    public function __construct(DeviceRepairOption $model)
    {
        $this->model = $model;
    }
}