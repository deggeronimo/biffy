<?php namespace Biffy\Entities\CompanyStoreItem;

use Biffy\Entities\AbstractEntity;

class CompanyStoreItem extends AbstractEntity
{
    protected $fillable = [
        'company_id',
        'store_item_id',
        'unit_price',
        'labor_cost'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function company()
    {
        return $this->belongsTo('Biffy\Entities\Company\Company');
    }

    public function storeItem()
    {
        return $this->belongsTo('Biffy\Entities\StoreItem\StoreItem');
    }
}