<?php

use Biffy\Entities\ApiKey\ApiKey;

class ApiKeysTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'X-Api-Key', 'key' => 'a1ee91688208f3aae70b3348652dc9bcb826eeb7' ]
    ];

    public function __construct(ApiKey $model)
    {
        $this->model = $model;
    }
}