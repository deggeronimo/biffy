<?php namespace Biffy\Entities\BodRecord;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentBodRecordRepository extends EloquentAbstractRepository implements BodRecordRepositoryInterface
{
    public function __construct(BodRecord $model)
    {
        $this->model = $model;
    }
}