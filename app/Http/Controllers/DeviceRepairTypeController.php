<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\DeviceRepairType\DeviceRepairTypeService;

class DeviceRepairTypeController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(DeviceRepairTypeService $service)
    {
        $this->service = $service;
    }
}