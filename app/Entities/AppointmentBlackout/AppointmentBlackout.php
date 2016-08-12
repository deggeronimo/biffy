<?php namespace Biffy\Entities\AppointmentBlackout;

use Biffy\Entities\AbstractEntity;

class AppointmentBlackout extends AbstractEntity
{
    protected $fillable = [
        'store_id',
        'year',
        'day_of_year',
        'day_of_week',
        'hour_of_day'
    ];

    public $timestamps = false;

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }
}