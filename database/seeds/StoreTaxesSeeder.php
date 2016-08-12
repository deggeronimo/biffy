<?php

use Biffy\Entities\StoreTax\StoreTax;

class StoreTaxesSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'store_id' => 25, 'name' => 'Florida State Tax', 'percentage' => '0.06', 'active' => 1 ],
        [ 'store_id' => 25, 'name' => 'Orange County Tax', 'percentage' => '0.005', 'active' => 1 ]
    ];

    public function __construct(StoreTax $model)
    {
        $this->model = $model;
    }
}