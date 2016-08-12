<?php namespace Biffy\Entities\ReceiveItem;

use Biffy\Entities\AbstractEntity;

class ReceiveItem extends AbstractEntity
{
    protected $fillable = [
        'purchase_item_id',
        'quantity'
    ];

    public function purchaseItem()
    {
        return $this->belongsTo('Biffy\Entities\PurchaseItem\PurchaseItem');
    }
}