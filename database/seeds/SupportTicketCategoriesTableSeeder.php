<?php

use Biffy\Entities\SupportTicketCategory\SupportTicketCategory;

class SupportTicketCategoriesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'bug' ],
        [ 'name' => 'duplicate' ],
        [ 'name' => 'enhancement' ],
        [ 'name' => 'help wanted' ],
        [ 'name' => 'invalid' ],
        [ 'name' => 'question' ],
        [ 'name' => 'wontfix' ]
    ];

    public function __construct(SupportTicketCategory $model)
    {
        $this->model = $model;
    }
}