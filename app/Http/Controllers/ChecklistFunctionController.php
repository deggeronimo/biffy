<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\ChecklistFunction\ChecklistFunctionService;

class ChecklistFunctionController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    private $service;

    public function __construct(ChecklistFunctionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $result = $this->service->all();
        return $this->data($result->toArray())->statusOk()->respond();
    }
}