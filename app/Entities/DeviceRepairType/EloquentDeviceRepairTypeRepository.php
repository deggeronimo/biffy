<?php namespace Biffy\Entities\DeviceRepairType;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentDeviceRepairTypeRepository extends EloquentAbstractRepository implements DeviceRepairTypeRepositoryInterface
{
    public function __construct(DeviceRepairType $model)
    {
        $this->model = $model;
    }
}