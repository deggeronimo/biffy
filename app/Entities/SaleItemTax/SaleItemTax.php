<?php namespace Biffy\Entities\SaleItemTax;

use Biffy\Entities\AbstractEntity;

class SaleItemTax extends AbstractEntity
{
    protected $fillable = [
        'sale_item_id',
        'store_tax_id'
    ];

    public function saleItem()
    {
        return $this->belongsTo('Biffy\Entities\SaleItem\SaleItem');
    }

    public function storeTax()
    {
        return $this->belongsTo('Biffy\Entities\StoreTax\StoreTax');
    }
} 