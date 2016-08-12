<?php namespace Biffy\Entities\Inventory;

use Biffy\Entities\AbstractEntity;
use CreateInventoryTable;

class Inventory extends AbstractEntity
{
    const STATUS_BACKORDERED = 3;
    const STATUS_PURCHASED = 2;
    const STATUS_RECEIVED = 1;

    protected $table = CreateInventoryTable::TABLENAME;

    protected $fillable = [
        'store_item_id',
        'sold_by_user_id',
        'cost',
        'status'
    ];

    public function saleItem()
    {
        return $this->hasOne('Biffy\Entities\SaleItem\SaleItem');
    }

    public function soldByUser()
    {
        return $this->belongsTo('Biffy\Entities\User\User', 'sold_by_user_id');
    }

    public function storeItem()
    {
        return $this->belongsTo('Biffy\Entities\StoreItem\StoreItem');
    }

    public function vendor()
    {
        return $this->belongsTo('Biffy\Entities\Vendor\Vendor');
    }
}