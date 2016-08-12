<?php namespace Biffy\Entities\DeviceRepairOptionItem;

use Biffy\Entities\AbstractEntity;

class DeviceRepairOptionItem extends AbstractEntity
{
    public $table = \CreateDeviceRepairOptionsItemsTable::TABLENAME;

    protected $fillable = [
        'device_repair_id',
        'device_repair_option_id',
        'item_id',
        'option_value',
        'image'
    ];

    protected $strings = [
        'estimated_cost'
    ];

    public $timestamps = false;

    public function deviceRepair()
    {
        return $this->belongsTo('Biffy\Entities\DeviceRepair\DeviceRepair');
    }

    public function deviceRepairOption()
    {
        return $this->belongsTo('Biffy\Entities\DeviceRepairOption\DeviceRepairOption');
    }

    public function item()
    {
        return $this->belongsTo('Biffy\Entities\Item\Item');
    }
}
