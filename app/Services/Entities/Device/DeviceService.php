<?php namespace Biffy\Services\Entities\Device;

use Biffy\Entities\Device\DeviceRepositoryInterface;
use Biffy\Services\Entities\Service;

/**
 * Class DeviceService
 * @package Biffy\Services\Entities\Device
 */
class DeviceService extends Service
{
    /**
     * @param DeviceRepositoryInterface $repo
     */
    public function __construct(DeviceRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}