<?php namespace Biffy\Entities\Action;

use Biffy\Entities\AbstractEntity;

class Action extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function workOrderNote()
    {
        return $this->hasMany('Biffy\Entities\WorkOrderNote');
    }
}