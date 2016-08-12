<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\DeviceRepair\DeviceRepairService;

class DeviceRepairController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(DeviceRepairService $service)
    {
        $this->service = $service;
    }
}