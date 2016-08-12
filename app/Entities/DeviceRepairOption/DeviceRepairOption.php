<?php namespace Biffy\Entities\DeviceRepairOption;

use Biffy\Entities\AbstractEntity;

class DeviceRepairOption extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}