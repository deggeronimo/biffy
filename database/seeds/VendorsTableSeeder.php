<?php

use Biffy\Entities\Vendor\Vendor;

class VendorsTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'Distribution Center', 'account_number' => '0123456789', 'contact_name' => 'Distro', 'contact_phone' => '321-445-8810' ],
        [ 'name' => 'Amazon', 'account_number' => '6498137502', 'contact_name' => 'Website', 'contact_phone' => 'amazon.com' ],
        [ 'name' => 'eBay', 'account_number' => '4916738205', 'contact_name' => 'Website', 'contact_phone' => 'eBay.com' ]
    ];

    public function __construct(Vendor $model)
    {
        $this->model = $model;
    }
}
