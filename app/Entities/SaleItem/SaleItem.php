<?php namespace Biffy\Entities\SaleItem;

use Biffy\Entities\AbstractEntity;

class SaleItem extends AbstractEntity
{
    protected $fillable = [
        'sale_id',
        'work_order_id',
        'inventory_id',
        'price',
        'labor_cost',
        'discount',
        'on_receipt',
        'tax_exempt',
        'name'
    ];

    public function sale()
    {
        return $this->belongsTo('Biffy\Entities\Sale\Sale');
    }

    public function inventory()
    {
        return $this->belongsTo('Biffy\Entities\Inventory\Inventory');
    }

    public function storeItem()
    {
        return $this->belongsToThrough('Biffy\Entities\StoreItem\StoreItem', 'Biffy\Entities\Inventory\Inventory');
    }

    public function taxes()
    {
        return $this->hasMany('Biffy\Entities\SaleItemTax\SaleItemTax');
    }

    public function workOrder()
    {
        return $this->belongsTo('Biffy\Entities\WorkOrder\WorkOrder');
    }
}