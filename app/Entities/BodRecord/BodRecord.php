<?php namespace Biffy\Entities\BodRecord;

use Biffy\Entities\AbstractEntity;

class BodRecord extends AbstractEntity
{
    protected $fillable = [
        'source_ip',
        'checklist',
        'store_id',
        'user_id'
    ];
}