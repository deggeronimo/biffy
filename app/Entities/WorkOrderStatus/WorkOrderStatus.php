<?php namespace Biffy\Entities\WorkOrderStatus;

use Biffy\Entities\AbstractEntity;
use CreateWorkorderStatusesTable;

class WorkOrderStatus extends AbstractEntity
{
    // todo cache and set dynamically
    const AWAITING_REPAIR = 1;
    const REPAIR_IN_PROGRESS = 2;
    const AWAITING_PARTS = 4;
    const REPAIRED_RFP = 6;
    const NEED_TO_ORDER_PARTS = 7;
    const DEVICE_ABANDONED = 10;
    const AWAITING_APPROVAL = 11;
    const DECLINED_RFP = 12;
    const SALE_COMPLETED = 13;
    const QUOTED = 14;
    const APPROVED = 15;

    protected $table = CreateWorkorderStatusesTable::TABLENAME;

    protected $fillable = [
        'name',
        'initial',
        'next_time',
        'action_text_key',
        'user_can_set',
        'remove_items'
    ];

    public $timestamps = false;

    public function workorder()
    {
        return $this->hasMany('Biffy\Entities\WorkOrder\WorkOrder');
    }
}