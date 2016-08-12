<?php namespace Biffy\Entities\Lead;

use Biffy\Entities\AbstractEntity;

class Lead extends AbstractEntity
{
    protected $fillable = [
        'given_name',
        'family_name',
        'phone',
        'email',
        'postal_code',
        'device',
        'issue',
        'price'
    ];

    protected $appends = [];

}