<?php namespace Biffy\Entities\ChecklistFunction;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentChecklistFunctionRepository extends EloquentAbstractRepository implements ChecklistFunctionRepositoryInterface
{
    public function __construct(ChecklistFunction $model)
    {
        $this->model = $model;
    }
}