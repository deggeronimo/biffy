<?php namespace Biffy\Services\Entities\DeviceRepairOption;

use Biffy\Entities\DeviceRepairOption\DeviceRepairOptionRepositoryInterface;
use Biffy\Services\Entities\Service;

class DeviceRepairOptionService extends Service
{
    public function __construct(DeviceRepairOptionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}