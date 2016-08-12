<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Store\StoreService;

/**
 * Class StoreController
 * @package Biffy\Http\Controllers
 */
class StoreController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var
     */
    protected $service;

    /**
     * @param StoreService $service
     */
    function __construct(StoreService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $sorting = $this->input('sorting');

        $result = $this->service->sortBy($sorting)->get ();

        return $this->data($result->toArray())->respond();
    }

} 