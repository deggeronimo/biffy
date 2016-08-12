<?php namespace Biffy\Entities\EodRecord;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentEodRecordRepository extends EloquentAbstractRepository implements EodRecordRepositoryInterface
{
    public function __construct(EodRecord $model)
    {
        $this->model = $model;
    }
}