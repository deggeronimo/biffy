<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\WebsiteFilter\WebsiteFilterService;

class WebsiteFilterController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(WebsiteFilterService $service)
    {
        $this->service = $service;
    }
}
