<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\DeviceRepairOption\DeviceRepairOptionService;

class DeviceRepairOptionController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(DeviceRepairOptionService $service)
    {
        $this->service = $service;
    }
}