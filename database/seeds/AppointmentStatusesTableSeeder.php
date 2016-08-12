<?php

use Biffy\Entities\AppointmentStatus\AppointmentStatus;

class AppointmentStatusesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'Pending' ],
        [ 'name' => 'Canceled' ]
    ];

    public function __construct(AppointmentStatus $model)
    {
        $this->model = $model;
    }
}
