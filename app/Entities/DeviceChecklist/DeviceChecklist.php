<?php namespace Biffy\Entities\DeviceChecklist;

use Biffy\Entities\AbstractEntity;

class DeviceChecklist extends AbstractEntity
{
    protected $fillable = [
        'device_type_id',
        'checklist_function_id',
        'item_id'
    ];

    public $timestamps = false;

    public function deviceType()
    {
        return $this->belongsTo('Biffy\Entities\DeviceType\DeviceType');
    }

    public function checklistFunction()
    {
        return $this->belongsTo('Biffy\Entities\ChecklistFunction\ChecklistFunction');
    }

    public function item()
    {
        return $this->belongsTo('Biffy\Entities\Item\Item');
    }
}
