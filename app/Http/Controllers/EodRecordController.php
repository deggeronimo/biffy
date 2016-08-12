<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\EodRecord\EodRecordService;

class EodRecordController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;

    public function __construct(EodRecordService $service)
    {
        $this->service = $service;
    }
}