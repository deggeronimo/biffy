<?php namespace Biffy\Entities\DeviceRepairOptionItem;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentDeviceRepairOptionItemRepository extends EloquentAbstractRepository implements DeviceRepairOptionItemRepositoryInterface
{
    protected $filters = [
        'device_repair_id' => [ 'device_repair_id = ?', ':value' ]
    ];

    public function __construct(DeviceRepairOptionItem $model)
    {
        $this->model = $model;

        $this->with([ 'deviceRepairOption', 'item' ]);
    }
}