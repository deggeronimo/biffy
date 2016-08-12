<?php namespace Biffy\Entities\DeviceType;

use Biffy\Entities\AbstractRepositoryInterface;

interface DeviceTypeRepositoryInterface extends AbstractRepositoryInterface
{
    public function getDeviceChecklist($deviceTypeId);
}