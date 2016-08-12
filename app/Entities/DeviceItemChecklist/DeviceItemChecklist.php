<?php namespace Biffy\Entities\DeviceItemChecklist;

use Biffy\Entities\AbstractEntity;

class DeviceItemChecklist extends AbstractEntity
{
    protected $fillable = [
        'device_type_id',
        'checklist_item_id',
    ];

    public $timestamps = false;

    public function deviceType()
    {
        return $this->belongsTo('Biffy\Entities\DeviceType\DeviceType');
    }

    public function checklistItem()
    {
        return $this->belongsTo('Biffy\Entities\ChecklistItem\ChecklistItem');
    }
}