<?php

use Biffy\Entities\Action\Action;

class ActionsSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'Follow Up With Customer' ],
        [ 'name' => 'Parts Arrive' ],
        [ 'name' => 'Order Parts' ],
        [ 'name' => 'Complete Repair' ]
    ];

    public function __construct(Action $model)
    {
        $this->model = $model;
    }
}