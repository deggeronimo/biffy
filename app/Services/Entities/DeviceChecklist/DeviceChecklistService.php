<?php namespace Biffy\Services\Entities\DeviceChecklist;

use Biffy\Entities\DeviceChecklist\DeviceChecklistRepositoryInterface;
use Biffy\Services\Entities\Service;

class DeviceChecklistService extends Service
{
    public function __construct(DeviceChecklistRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}