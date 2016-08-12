<?php namespace Biffy\Entities\Item;

use Biffy\Entities\AbstractEntity;

class Item extends AbstractEntity
{
    protected $fillable = [
        'id', // todo temporary
        'item_number',
        'unit_price',
        'labor_cost',
        'distro_price',
        'name',
        'global',
        'item_type_id',
        'vendor_id',
        'device_type_id',
        'required'
    ];

    protected $hidden = [
        'old_item_id',
        'created_at',
        'updated_at'
    ];

    public function deviceChecklist()
    {
        return $this->hasMany('Biffy\Entities\DeviceChecklist\DeviceChecklist');
    }

    public function deviceType()
    {
        return $this->belongsTo('Biffy\Entities\DeviceType\DeviceType')->strings();
    }

    public function setDeviceTypeIdAttribute($value)
    {
        $this->attributes['device_type_id'] = $value ?: null;
    }

    public function stores()
    {
        return $this->belongsToMany('Biffy\Entities\Store\Store', 'store_items')
            ->withPivot(['stock', 'on_order', 'last_cost', 'unit_price', 'labor_cost'])
            ->withTimestamps();
    }

    public function storeItem()
    {
        return $this->hasMany('Biffy\Entities\StoreItem\StoreItem');
    }
}