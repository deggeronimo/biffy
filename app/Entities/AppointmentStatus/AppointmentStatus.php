<?php namespace Biffy\Entities\AppointmentStatus;

use Biffy\Entities\AbstractEntity;

class AppointmentStatus extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function appointments()
    {
        return $this->hasMany('Biffy\Entities\Appointment\Appointment');
    }
}