<?php namespace Biffy\Entities\PurchaseOrder;

use Biffy\Entities\AbstractEntity;

class PurchaseOrder extends AbstractEntity
{
    protected $fillable = [
        'store_id',
        'subtotal',
        'taxes',
        'currency_rate',
        'shipping_cost',
        'vendor_id',
        'tracking_number',
        'finalized',
        'shipping_method'
    ];

    public function purchaseItems()
    {
        return $this->hasMany('Biffy\Entities\PurchaseItem\PurchaseItem');
    }

    public function receiveItems()
    {
        return $this->hasManyThrough('Biffy\Entities\ReceiveItem\ReceiveItem', 'Biffy\Entities\PurchaseItem\PurchaseItem');
    }

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }

    public function vendor()
    {
        return $this->belongsTo('Biffy\Entities\Vendor\Vendor');
    }
}
