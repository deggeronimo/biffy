<?php

use Biffy\Services\Entities\DeviceFamily\DeviceFamilyService;

class DeviceFamiliesTableSeeder2 extends \Illuminate\Database\Seeder
{
    protected $itemList = [
        [ 'id' => 24, 'name' => 'iPhone' ],
        [ 'id' => 25, 'name' => 'Samsung Galaxy' ],
        [ 'id' => 65, 'name' => 'iPod' ],
        [ 'id' => 66, 'name' => 'iPad' ],
        [ 'id' => 67, 'name' => 'Kindle' ],
        [ 'id' => 72, 'name' => 'HTC One' ],
        [ 'id' => 73, 'name' => 'Nexus' ],
        [ 'id' => 74, 'name' => 'Dell Inspiron' ],
        [ 'id' => 75, 'name' => 'Toshiba Satellite' ],
        [ 'id' => 76, 'name' => 'MacBook' ],
        [ 'id' => 77, 'name' => 'MacBook Pro' ],
        [ 'id' => 78, 'name' => 'iBook' ],
        [ 'id' => 79, 'name' => 'MacBook Air' ],
        [ 'id' => 80, 'name' => 'HP Pavilion' ],
        [ 'id' => 81, 'name' => 'Dell Latitude' ],
        [ 'id' => 82, 'name' => 'Chromebook' ],
        [ 'id' => 83, 'name' => 'Acer Aspire' ],
        [ 'id' => 84, 'name' => 'Lenovo IdeaPad' ],
        [ 'id' => 85, 'name' => 'Lenovo ThinkPad' ],
        [ 'id' => 87, 'name' => 'Droid' ],
        [ 'id' => 88, 'name' => 'Nokia Lumia' ],
        [ 'id' => 89, 'name' => 'HTC EVO' ],
        [ 'id' => 90, 'name' => 'HTC Incredible' ],
        [ 'id' => 91, 'name' => 'BlackBerry Bold' ],
        [ 'id' => 92, 'name' => 'BlackBerry Curve' ],
        [ 'id' => 93, 'name' => 'Playstation' ],
        [ 'id' => 94, 'name' => 'XBOX' ],
        [ 'id' => 95, 'name' => 'Wii' ],
        [ 'id' => 100, 'name' => 'Asus Memo' ],
        [ 'id' => 111, 'name' => 'Asus Transformer' ]
    ];

    public function __construct(DeviceFamilyService $service)
    {
        $this->service = $service;
    }
}