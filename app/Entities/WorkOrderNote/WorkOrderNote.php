<?php namespace Biffy\Entities\WorkOrderNote;

use Biffy\Entities\AbstractEntity;
use CreateWorkorderNotesTable;

class WorkOrderNote extends AbstractEntity
{
    protected $table = CreateWorkorderNotesTable::TABLENAME;

    protected $fillable = [
        'public',
        'work_order_id',
        'user_id',
        'workorder_status_id',
        'action_id',
        'next_update_time',
        'notes',
        'auto',
        'diag'
    ];

    public function action()
    {
        return $this->belongsTo('Biffy\Entities\Action\Action');
    }

    public function workorderStatus()
    {
        return $this->belongsTo('Biffy\Entities\WorkOrderStatus\WorkOrderStatus');
    }

    public function user()
    {
        return $this->belongsTo('Biffy\Entities\User\User');
    }

    public function workOrder()
    {
        return $this->belongsTo('Biffy\Entities\WorkOrder\WorkOrder');
    }
}