<?php

use Biffy\Entities\FileCategory\FileCategory;

class FileCategoriesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'In-store graphics' ],
        [ 'name' => 'Advertisement' ],
        [ 'name' => 'Branding' ]
    ];

    public function __construct(FileCategory $model)
    {
        $this->model = $model;
    }
}