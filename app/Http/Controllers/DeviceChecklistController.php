<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\DeviceChecklist\DeviceChecklistService;
use Biffy\Services\Entities\DeviceType\DeviceTypeService;

class DeviceChecklistController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    private $deviceTypeService;

    function __construct(DeviceChecklistService $service, DeviceTypeService $deviceTypeService)
    {
        $this->service = $service;
        $this->deviceTypeService = $deviceTypeService;
    }

    function index()
    {
        if (!is_null($this->input('full')))
        {
            $filter = $this->input('filter');
            $sorting = $this->input('sorting');

            $result = $this->service->getList($filter, $sorting);

            return $this->data($result->toArray())->respond();
        }

        if (is_null($this->input('selectable')))
        {
            $deviceTypeId = $this->input('device_type_id');
            $result = $this->deviceTypeService->getDeviceChecklist($deviceTypeId);
            return $this->data($result)->statusOk()->respond();
        }
        else
        {
            $result = $this->deviceTypeService->getSelectableDeviceTypes();
            return $this->data($result->toArray())->statusOk()->respond();
        }
    }
}