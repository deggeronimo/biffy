<?php namespace Biffy\Http\Controllers;

use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\Device\DeviceService;

/**
 * Class DeviceController
 * @package Biffy\Http\Controllers
 */
class DeviceController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var DeviceService
     */
    protected $service;

    /**
     * @param DeviceService $service
     */
    function __construct(DeviceService $service)
    {
        $this->service = $service;
    }

    /**
     * @param AbstractFormRequest $request
     * @return mixed
     */
    public function store(AbstractFormRequest $request)
    {
        $result = $this->service->create($request->all());

        return $this->data($result)->statusCreated()->respond();
    }
}