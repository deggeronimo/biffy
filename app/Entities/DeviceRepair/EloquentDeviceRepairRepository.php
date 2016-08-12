<?php namespace Biffy\Entities\DeviceRepair;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentDeviceRepairRepository extends EloquentAbstractRepository implements DeviceRepairRepositoryInterface
{
    /**
     * @var array
     */
    protected $filters = [
        'device_type_id' => [ 'device_type_id = ?', ':value' ],
        'name' => [ 'ls_name.string like ?', '%:value%' ]
    ];

    public function __construct(DeviceRepair $model)
    {
        $this->model = $model;

        $this->with([ 'deviceRepairType', 'deviceType', 'item', 'deviceRepairOptionItems', 'deviceRepairOptionItems.item' ]);
    }
}