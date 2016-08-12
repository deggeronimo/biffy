<?php

use Biffy\Entities\WebsiteFilterGroup\WebsiteFilterGroup;
use Biffy\Services\Entities\WebsiteFilterGroup\WebsiteFilterGroupService;

class WebsiteFilterGroupsTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 1, 'sort_order' => 5, 'portal_filter' => 'os_id' ],
        [ 'id' => 2, 'sort_order' => 1, 'portal_filter' => 'parent_device_type_id' ],
        [ 'id' => 3, 'sort_order' => 2, 'portal_filter' => 'device_manufacturer_id' ],
        [ 'id' => 4, 'sort_order' => 3, 'portal_filter' => 'carrier_id' ],
        [ 'id' => 5, 'sort_order' => 4, 'portal_filter' => 'device_family_id' ],
        [ 'id' => 6, 'sort_order' => 6, 'portal_filter' => 'device_display_size_id' ]
    ];

    protected $stringList = [
        [ 'id' => 1, 'values' => [ 'name' => [ 'en' => 'Operating System', 'ca' => 'Operating System', 'ca_fr' => 'Système d\'exploitation', 'tt' => 'Operating System' ] ] ],
        [ 'id' => 2, 'values' => [ 'name' => [ 'en' => 'Device Type', 'ca' => 'Device Type', 'ca_fr' => 'Type de Périphérique', 'tt' => 'Device Type' ] ] ],
        [ 'id' => 3, 'values' => [ 'name' => [ 'en' => 'Manufacturer', 'ca' => 'Manufacturer', 'ca_fr' => 'Fabricant', 'tt' => 'Manufacturer' ] ] ],
        [ 'id' => 4, 'values' => [ 'name' => [ 'en' => 'Wireless Carrier', 'ca' => 'Wireless Carrier', 'ca_fr' => 'Opérateur sans fil', 'tt' => 'Wireless Carrier' ] ] ],
        [ 'id' => 5, 'values' => [ 'name' => [ 'en' => 'Family', 'ca' => 'Family', 'ca_fr' => 'Famille', 'tt' => 'Family' ] ] ],
        [ 'id' => 6, 'values' => [ 'name' => [ 'en' => 'Display Size', 'ca' => 'Display Size', 'ca_fr' => 'Taille de l\'écran', 'tt' => 'Display Size' ] ] ],
    ];

    public function __construct(WebsiteFilterGroupService $service, WebsiteFilterGroup $model)
    {
        $this->service = $service;

        $this->model = $model;
    }
}
