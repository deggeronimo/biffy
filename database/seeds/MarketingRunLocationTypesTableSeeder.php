<?php

use Biffy\Entities\MarketingLocationType\MarketingLocationType;

class MarketingRunLocationTypesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'AT&T' ],
        [ 'name' => 'Verizon' ],
        [ 'name' => 'T-Mobile' ],
        [ 'name' => 'Sprint' ],
        [ 'name' => 'Radio Shack' ],
        [ 'name' => 'Game Stop' ]
    ];

    public function __construct(MarketingLocationType $model)
    {
        $this->model = $model;
    }
}