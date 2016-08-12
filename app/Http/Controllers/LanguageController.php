<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Language\LanguageService;

class LanguageController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(LanguageService $service)
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