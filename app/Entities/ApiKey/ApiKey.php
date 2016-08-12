<?php namespace Biffy\Entities\ApiKey;

use Biffy\Entities\AbstractEntity;

class ApiKey extends AbstractEntity
{
    protected $fillable = [
        'name',
        'key'
    ];
}