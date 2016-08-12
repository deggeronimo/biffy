<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\DeviceType\DeviceTypeRepositoryInterface;
use Biffy\Facades\StoreConfig;
use Biffy\Services\Entities\DeviceItemChecklist\DeviceItemChecklistService;

class DeviceItemChecklistController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    private $repo;

    function __construct(DeviceItemChecklistService $service, DeviceTypeRepositoryInterface $repo)
    {
        $this->service = $service;
        $this->repo = $repo;
    }

    function index()
    {
        if (!is_null($this->input('full')))
        {
            $filter = $this->input('filter');
            $sorting = $this->input('sorting');

            //@todo Make new config setting for count per page
            $perPage = StoreConfig::get('results-per-page');
            $perPage = (is_null($perPage)?10:$perPage);

            $count = $this->input('count', $perPage);
            $page = $this->input('page', 1);

            $result = $this->service->getIndex($count, $page, $filter, $sorting);

            return $this->data($result->toArray()['data'])->paginator($result)->respond();
        }

        $deviceTypeId = $this->input('device_type_id');
        $result = $this->repo->getDeviceItemChecklist($deviceTypeId);
        return $this->data($result)->statusOk()->respond();
    }
}