<?php namespace Biffy\Entities\Setting;

use Biffy\Entities\AbstractEntity;

class Setting extends AbstractEntity
{
    protected $fillable = [
        'name',
        'key',
        'type',
        'extra',
        'default'
    ];

    public $timestamps = false;
}