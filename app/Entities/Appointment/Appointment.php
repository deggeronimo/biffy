<?php namespace Biffy\Entities\Appointment;

use Biffy\Entities\AbstractEntity;

class Appointment extends AbstractEntity
{
    protected $fillable = [
        'issue',
        'appointment_status_id',
        'store_id',
        'customer_id',
        'time'
    ];

    public function appointmentStatus()
    {
        return $this->belongsTo('Biffy\Entities\AppointmentStatus\AppointmentStatus');
    }

    public function customer()
    {
        return $this->belongsTo('Biffy\Entities\Customer\Customer');
    }

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }
}