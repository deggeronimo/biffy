<?php

use Biffy\Entities\Language\Language;

class LanguagesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'id' => 1, 'lang' => 'en' ],
        [ 'id' => 2, 'lang' => 'ca_fr' ],
        [ 'id' => 3, 'lang' => 'tt' ],
        [ 'id' => 4, 'lang' => 'ca' ]
    ];

    public function __construct(Language $model)
    {
        $this->model = $model;
    }
}