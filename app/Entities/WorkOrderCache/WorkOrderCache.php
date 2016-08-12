<?php namespace Biffy\Entities\WorkOrderCache;

use Biffy\Entities\AbstractEntity;
use CreateWorkorderCachesTable;

class WorkOrderCache extends AbstractEntity
{
    protected $table = CreateWorkorderCachesTable::TABLENAME;

    protected $fillable = [
        'negative_stock',
        'work_order_id'
    ];

    public function workOrder()
    {
        return $this->belongsTo('Biffy\Entities\WorkOrder\WorkOrder');
    }
}