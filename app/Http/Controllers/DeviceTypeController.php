<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\DeviceType\DeviceTypeService;

class DeviceTypeController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    function __construct(DeviceTypeService $service)
    {
        $this->service = $service;
    }
}