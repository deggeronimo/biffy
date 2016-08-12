<?php namespace Biffy\Http\Controllers; 

use Biffy\Http\Controllers\Helpers\ServiceListControllerHelper;
use Biffy\Services\Entities\WorkorderStatus\WorkorderStatusService;

class WorkorderStatusController extends ApiController
{
    use ServiceListControllerHelper;

    public function __construct(WorkorderStatusService $service)
    {
        $this->service = $service;
    }
}