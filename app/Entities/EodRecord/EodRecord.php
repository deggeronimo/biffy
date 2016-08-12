<?php namespace Biffy\Entities\EodRecord;

use Biffy\Entities\AbstractEntity;

class EodRecord extends AbstractEntity
{
    protected $fillable = [
        'source_ip',
        'checklist',
        'store_id',
        'user_id'
    ];
}