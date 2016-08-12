<?php namespace Biffy\Entities\WorkOrderCache;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentWorkOrderCacheRepository extends EloquentAbstractRepository implements WorkOrderCacheRepositoryInterface
{
    public function __construct(WorkOrderCache $model)
    {
        $this->model = $model;
    }
}