<?php namespace Biffy\Entities\Config;

use Biffy\Entities\AbstractEntity;
use CreateConfigTable;

class Config extends AbstractEntity
{
    public $table = CreateConfigTable::TABLENAME;

    protected $fillable = [
        'name',
        'type',
        'extra',
        'key',
        'default'
    ];

    public function storeConfig()
    {
        return $this->hasMany('Biffy\Entities\StoreConfig\StoreConfig');
    }
} 