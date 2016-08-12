<?php namespace Biffy\Entities\DeviceFamily;

use Biffy\Entities\AbstractEntity;

class DeviceFamily extends AbstractEntity
{
    protected $fillable = [
        'id',
        'name'
    ];

    public $timestamps = false;
}