<?php namespace Biffy\Entities\WorkOrderNote;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentWorkOrderNoteRepository
 * @package Biffy\Entities\WorkOrderNote
 */
class EloquentWorkOrderNoteRepository extends EloquentAbstractRepository implements WorkOrderNoteRepositoryInterface
{
    protected $sorters = [
        'created_at' => []
    ];

    /**
     * @param WorkOrderNote $model
     */
    public function __construct(WorkOrderNote $model)
    {
        $this->model = $model;

        $this->with([ 'user' ]);
    }

    /**
     * @param int $workOrderId
     * @return mixed
     */
    public function getWorkOrderNoteList($workOrderId)
    {
        return $this->model->with([ 'user' ])->where('work_order_id', '=', $workOrderId)->get();
    }
}