<?php namespace Biffy\Entities\DeviceRepair;

use Biffy\Entities\AbstractEntity;
use CreateDeviceRepairsTable;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceRepair extends AbstractEntity
{
    protected $table = CreateDeviceRepairsTable::TABLENAME;

    protected $fillable = [
        'id',
        'device_type_id',
        'device_repair_type_id',
        'item_id',
        'image',
        'sort_order',
        'view_count',
        'status',
        'keyword'
    ];

    public $timestamps = false;

    protected $strings = [
        'name',
        'estimated_cost',
        'meta_description',
        'meta_keywords',
        'web_description'
    ];

    use SoftDeletes;

    public function deviceType()
    {
        return $this->belongsTo('Biffy\Entities\DeviceType\DeviceType')->strings();
    }

    public function deviceRepairType()
    {
        return $this->belongsTo('Biffy\Entities\DeviceRepairType\DeviceRepairType');
    }

    public function deviceRepairOptionItems()
    {
        return $this->hasMany('Biffy\Entities\DeviceRepairOptionItem\DeviceRepairOptionItem');
    }

    public function item()
    {
        return $this->belongsTo('Biffy\Entities\Item\Item');
    }
}
