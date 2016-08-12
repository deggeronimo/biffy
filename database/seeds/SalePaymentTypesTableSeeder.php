<?php

use Biffy\Entities\SalePaymentType\SalePaymentType;

class SalePaymentTypesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'Cash' ],
        [ 'name' => 'Credit' ],
        [ 'name' => 'Gift Card' ],
        [ 'name' => 'Adjustment Amount' ],
        [ 'name' => 'Adjustment Discount' ],
        [ 'name' => 'Adjustment Comp' ],
        [ 'name' => 'Trade Credit' ],
        [ 'name' => 'Cash Refund' ],
        [ 'name' => 'Credit Refund' ],
        [ 'name' => 'Check' ]
    ];

    public function __construct(SalePaymentType $model)
    {
        $this->model = $model;
    }
}