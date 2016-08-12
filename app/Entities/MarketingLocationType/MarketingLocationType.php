<?php namespace Biffy\Entities\MarketingLocationType;

use Biffy\Entities\AbstractEntity;

class MarketingLocationType extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}