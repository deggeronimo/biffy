<?php namespace Biffy\Entities\PurchaseItem;

use Biffy\Entities\AbstractEntity;

class PurchaseItem extends AbstractEntity
{
    protected $fillable = [
        'purchase_order_id',
        'store_item_id',
        'quantity',
        'cost'
    ];

    public function backOrderItems()
    {
        return $this->belongsTo('Biffy\Entities\StoreItem\StoreItem', 'store_item_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('Biffy\Entities\PurchaseOrder\PurchaseOrder');
    }

    public function receiveItems()
    {
        return $this->hasMany('Biffy\Entities\ReceiveItem\ReceiveItem');
    }

    public function storeItem()
    {
        return $this->belongsTo('Biffy\Entities\StoreItem\StoreItem');
    }

}