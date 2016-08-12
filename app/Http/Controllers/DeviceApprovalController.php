<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\DeviceApproval\DeviceApprovalService;

class DeviceApprovalController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(DeviceApprovalService $service)
    {
        $this->service = $service;
    }
}