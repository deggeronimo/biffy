<?php namespace Biffy\Entities\WorkOrder;

use Biffy\Entities\AbstractEntity;
use CreateWorkordersTable;

class WorkOrder extends AbstractEntity
{
    protected $table = CreateWorkordersTable::TABLENAME;

    protected $fillable = [
        'quickdiag',
        'itemswithdevice',
        'notes',
        'rating',
        'queue',
        'next_update',
        'device_id',
        'sale_id',
        'workorder_status_id',
        'assigned_to_user_id',
        'warranty_workorder_id'
    ];

    public function assignedToUser()
    {
        return $this->belongsTo('Biffy\Entities\User\User', 'assigned_to_user_id');
    }

    public function device()
    {
        return $this->belongsTo('Biffy\Entities\Device\Device');
    }

    public function sale()
    {
        return $this->belongsTo('Biffy\Entities\Sale\Sale');
    }

    public function saleItems()
    {
        return $this->hasMany('Biffy\Entities\SaleItem\SaleItem');
    }

    public function workOrderCache()
    {
        return $this->hasOne('Biffy\Entities\WorkOrderCache\WorkOrderCache');
    }

    public function workorderNotes()
    {
        return $this->hasMany('Biffy\Entities\WorkOrderNote\WorkOrderNote');
    }

    public function workorderStatus()
    {
        return $this->belongsTo('Biffy\Entities\WorkOrderStatus\WorkOrderStatus');
    }
}