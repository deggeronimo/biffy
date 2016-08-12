<?php

use Biffy\Entities\AppointmentBlackout\AppointmentBlackout;

class AppointmentBlackoutsTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'store_id' => null, 'year' => null, 'day_of_year' => null, 'day_of_week' => null, 'hour_of_day' => 24 ],      //12:00 to 12:29 every day
        [ 'store_id' => null, 'year' => 2015, 'day_of_year' => 1,    'day_of_week' => null, 'hour_of_day' => null ],    //January 1, 2015 (New Year's Day)
        [ 'store_id' => null, 'year' => 2015, 'day_of_year' => 95,   'day_of_week' => null, 'hour_of_day' => null ],    //April 5, 2015 (Easter)
        [ 'store_id' => null, 'year' => 2015, 'day_of_year' => 185,  'day_of_week' => null, 'hour_of_day' => null ],    //July 4, 2015
        [ 'store_id' => null, 'year' => 2015, 'day_of_year' => 330,  'day_of_week' => null, 'hour_of_day' => null ],    //November 28, 2015 (Thanksgiving)
        [ 'store_id' => null, 'year' => 2015, 'day_of_year' => 358,  'day_of_week' => null, 'hour_of_day' => null ],    //December 24, 2015 (Christmas Eve)
        [ 'store_id' => null, 'year' => 2015, 'day_of_year' => 359,  'day_of_week' => null, 'hour_of_day' => null ],    //December 25, 2015 (Christmas Day)
        [ 'store_id' => null, 'year' => 2015, 'day_of_year' => 365,  'day_of_week' => null, 'hour_of_day' => null ],    //December 31, 2015 (New Year's Eve)
        [ 'store_id' => null, 'year' => null, 'day_of_year' => null, 'day_of_week' => 6,    'hour_of_day' => null ],    //December 31, 2015 (New Year's Eve)
    ];

    public function __construct(AppointmentBlackout $model)
    {
        $this->model = $model;
    }
}