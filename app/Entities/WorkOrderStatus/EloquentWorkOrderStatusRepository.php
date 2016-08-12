<?php namespace Biffy\Entities\WorkOrderStatus;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentWorkOrderStatusRepository extends EloquentAbstractRepository implements WorkOrderStatusRepositoryInterface
{
    protected $filters = ['user_can_set' => ['user_can_set = ?', ':value']];

    public function __construct(WorkOrderStatus $model)
    {
        $this->model = $model;
    }
}