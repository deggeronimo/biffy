<?php namespace Biffy\Entities\DeviceApproval;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentDeviceApprovalRepository extends EloquentAbstractRepository implements DeviceApprovalRepositoryInterface
{
    public function __construct(DeviceApproval $model)
    {
        $this->model = $model;
    }

    public function create($attributes)
    {
        $attributes['scrub_device_name'] = $attributes['device_name'];
        $attributes['scrub_manufacturer_name'] = $attributes['manufacturer_name'];
        $attributes['scrub_carrier_name'] = $attributes['carrier_name'];

        return parent::create($attributes);
    }
}