<?php namespace Biffy\Entities\DeviceRepairOption;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentDeviceRepairOptionRepository extends EloquentAbstractRepository implements DeviceRepairOptionRepositoryInterface
{
    protected $sorters = [
        'name' => []
    ];

    public function __construct(DeviceRepairOption $model)
    {
        $this->model = $model;
    }
}