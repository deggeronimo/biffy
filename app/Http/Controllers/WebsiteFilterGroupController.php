<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\WebsiteFilterGroup\WebsiteFilterGroupService;

class WebsiteFilterGroupController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(WebsiteFilterGroupService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $result = $this->service->all();

        return $this->data($result->toArray())->respond();
    }
}