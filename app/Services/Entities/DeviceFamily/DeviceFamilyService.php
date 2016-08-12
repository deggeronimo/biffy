<?php namespace Biffy\Services\Entities\DeviceFamily;

use Biffy\Entities\DeviceFamily\DeviceFamilyRepositoryInterface;
use Biffy\Services\Entities\Service;

class DeviceFamilyService extends Service
{
    public function __construct(DeviceFamilyRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}