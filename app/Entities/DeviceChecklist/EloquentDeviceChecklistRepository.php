<?php namespace Biffy\Entities\DeviceChecklist;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentDeviceChecklistRepository extends EloquentAbstractRepository implements DeviceChecklistRepositoryInterface
{
    public function __construct(DeviceChecklist $model)
    {
        $this->model = $model;

        $this->with([ 'deviceType', 'checklistFunction', 'item' ]);
    }
}
