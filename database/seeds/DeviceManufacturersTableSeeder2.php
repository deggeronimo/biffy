<?php

use Biffy\Services\Entities\DeviceManufacturer\DeviceManufacturerService;

class DeviceManufacturersTableSeeder2 extends \Illuminate\Database\Seeder
{
    protected $itemList = [
        [ 'id' => 12, 'name' => 'Apple' ],
        [ 'id' => 14, 'name' => 'HTC' ],
        [ 'id' => 15, 'name' => 'Google' ],
        [ 'id' => 16, 'name' => 'Samsung' ],
        [ 'id' => 17, 'name' => 'Motorola' ],
        [ 'id' => 18, 'name' => 'Blackberry' ],
        [ 'id' => 19, 'name' => 'Dell' ],
        [ 'id' => 20, 'name' => 'Amazon' ],
        [ 'id' => 30, 'name' => 'HP' ],
        [ 'id' => 31, 'name' => 'Acer' ],
        [ 'id' => 32, 'name' => 'Asus' ],
        [ 'id' => 33, 'name' => 'Nokia' ],
        [ 'id' => 34, 'name' => 'LG' ],
        [ 'id' => 35, 'name' => 'Gateway' ],
        [ 'id' => 36, 'name' => 'Sony' ],
        [ 'id' => 37, 'name' => 'Microsoft' ],
        [ 'id' => 38, 'name' => 'Alienware' ],
        [ 'id' => 40, 'name' => 'Toshiba' ],
        [ 'id' => 41, 'name' => 'Barnes and Noble' ],
        [ 'id' => 42, 'name' => 'Palm' ],
        [ 'id' => 43, 'name' => 'Casio' ],
        [ 'id' => 86, 'name' => 'Lenovo' ],
        [ 'id' => 96, 'name' => 'Nintendo' ],
        [ 'id' => 97, 'name' => 'Sega' ]
    ];

    public function __construct(DevicemanufacturerService $service)
    {
        $this->service = $service;
    }
}
