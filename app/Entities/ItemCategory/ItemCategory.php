<?php namespace Biffy\Entities\ItemCategory;

use Biffy\Entities\AbstractEntity;

class ItemCategory extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function items()
    {
        return $this->hasMany('Biffy\Entities\Item\Item');
    }
}