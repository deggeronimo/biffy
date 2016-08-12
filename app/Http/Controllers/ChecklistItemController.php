<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\ChecklistItem\ChecklistItemService;

class ChecklistItemController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    private $service;

    public function __construct(ChecklistItemService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $result = $this->service->all();
        return $this->data($result->toArray())->statusOk()->respond();
    }
}