<?php namespace Biffy\Entities\ChecklistItem;

use Biffy\Entities\AbstractEntity;

class ChecklistItem extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function deviceChecklist()
    {
        return $this->hasMany('Biffy\Entities\DeviceItemChecklist\DeviceItemChecklist');
    }
}
