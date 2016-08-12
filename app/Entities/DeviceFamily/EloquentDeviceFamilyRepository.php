<?php namespace Biffy\Entities\DeviceFamily;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentDeviceFamilyRepository extends EloquentAbstractRepository implements DeviceFamilyRepositoryInterface
{
    protected $filters = [
        'name' => ['name like ?', '%:value%'],
        'search' => ['name like ?', '%:value%']
    ];

    protected $sorters = [
        'name' => [],
    ];

    public function __construct(DeviceFamily $model)
    {
        $this->model = $model;
    }
}