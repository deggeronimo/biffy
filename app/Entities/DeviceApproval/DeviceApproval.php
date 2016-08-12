<?php namespace Biffy\Entities\DeviceApproval;

use Biffy\Entities\AbstractEntity;

class DeviceApproval extends AbstractEntity
{
    protected $fillable = [
        'scrub_device_name',
        'scrub_manufacturer_name',
        'scrub_carrier_name',
        'device_name',
        'manufacturer_name',
        'carrier_name',
        'approved'
    ];
}