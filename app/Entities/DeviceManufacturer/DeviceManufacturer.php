<?php namespace Biffy\Entities\DeviceManufacturer;

use Biffy\Entities\AbstractEntity;

class DeviceManufacturer extends AbstractEntity
{
    protected $fillable = [
        'id',
        'name'
    ];

    public $timestamps = false;
}