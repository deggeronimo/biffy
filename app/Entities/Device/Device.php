<?php namespace Biffy\Entities\Device;

use Biffy\Entities\AbstractEntity;

class Device extends AbstractEntity
{
    protected $fillable = [
        'name',
        'passcode',
        'serial',
        'serial_type',
        'customer_id',
        'device_type_id'
    ];

    public function customer()
    {
        return $this->belongsTo('Biffy\Entities\Customer\Customer');
    }

    public function deviceType()
    {
        return $this->belongsTo('Biffy\Entities\DeviceType\DeviceType');
    }

    public function workOrders()
    {
        return $this->hasMany('Biffy\Entities\WorkOrder\WorkOrder');
    }
}