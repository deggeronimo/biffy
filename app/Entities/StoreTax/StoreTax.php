<?php namespace Biffy\Entities\StoreTax;

use Biffy\Entities\AbstractEntity;

class StoreTax extends AbstractEntity
{
    protected $fillable = [
        'store_id',
        'name',
        'percentage',
        'active'
    ];

    public function saleItemTax()
    {
        return $this->hasMany('Biffy\Entities\SaleItemTax\SaleItemTax');
    }

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }
} 