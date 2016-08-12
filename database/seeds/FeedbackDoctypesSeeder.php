<?php

use Biffy\Entities\FeedbackDoctype\FeedbackDoctype;

class FeedbackDoctypesSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'Receipt Image' ],
        [ 'name' => 'Device Image' ],
        [ 'name' => 'Other' ]
    ];

    public function __construct(FeedbackDoctype $model)
    {
        $this->model = $model;
    }
}

