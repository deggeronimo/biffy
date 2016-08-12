<?php

use Biffy\Entities\AccountExpenseCategory\AccountExpenseCategory;

class AccountExpenseCategoriesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'Inventory' ],
        [ 'name' => 'Marketing' ],
        [ 'name' => 'Store Supplies' ],
        [ 'name' => 'Postage and Delivery' ],
        [ 'name' => 'Repairs and Maintenance' ],
        [ 'name' => 'Other' ]
    ];

    public function __construct(AccountExpenseCategory $model)
    {
        $this->model = $model;
    }
}