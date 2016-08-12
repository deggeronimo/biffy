<?php namespace Biffy\Entities\StoreItem;

use Biffy\Entities\AbstractEntity;

class StoreItem extends AbstractEntity
{
    protected $fillable = [
        'item_id',
        'store_id',
        'stock',
        'on_order',
        'reserved',
        'last_cost',
        'unit_price',
        'labor_cost'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function companyStoreItems()
    {
        return $this->hasMany('Biffy\Entities\CompanyStoreItem\CompanyStoreItem');
    }

    public function item()
    {
        return $this->belongsTo('Biffy\Entities\Item\Item');
    }

    public function inventory()
    {
        return $this->hasMany('Biffy\Entities\Inventory\Inventory');
    }

    public function purchaseItem()
    {
        return $this->hasMany('Biffy\Entities\PurchaseItem\PurchaseItem');
    }

    public function purchaseOrders()
    {
        return $this->hasMany('Biffy\Entities\PurchaseOrder\PurchaseOrder');
    }

    public function saleItems()
    {
        return $this->hasManyThrough('Biffy\Entities\SaleItem\SaleItem', 'Biffy\Entities\Inventory\Inventory');
    }

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }
}