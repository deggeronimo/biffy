<?php namespace Biffy\Entities\ChecklistFunction;

use Biffy\Entities\AbstractEntity;

class ChecklistFunction extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function deviceChecklist()
    {
        return $this->hasMany('Biffy\Entities\DeviceChecklist\DeviceChecklist');
    }
}