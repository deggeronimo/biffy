<?php

use Biffy\Services\Entities\ChecklistFunction\ChecklistFunctionService;

class ChecklistFunctionTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 1, 'name' => 'Touch Responsive' ],
        [ 'id' => 2, 'name' => 'LCD Display' ],
        [ 'id' => 3, 'name' => 'Power Button' ]
    ];

    public function __construct(ChecklistFunctionService $service)
    {
        $this->service = $service;
    }
}