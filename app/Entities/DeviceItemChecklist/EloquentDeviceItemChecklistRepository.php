<?php namespace Biffy\Entities\DeviceItemChecklist;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentDeviceItemChecklistRepository extends EloquentAbstractRepository implements DeviceItemChecklistRepositoryInterface
{
    public function __construct(DeviceItemChecklist $model)
    {
        $this->model = $model;

        $this->with([ 'deviceType', 'checklistItem' ]);
    }
}