<?php namespace Biffy\Entities\DeviceManufacturer;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentDeviceManufacturerRepository extends EloquentAbstractRepository implements DeviceManufacturerRepositoryInterface
{
    protected $filters = [
        'name' => ['name like ?', '%:value%'],
        'search' => ['name like ?', '%:value%']
    ];

    protected $sorters = [
        'name' => [],
    ];

    public function __construct(DeviceManufacturer $model)
    {
        $this->model = $model;
    }
}