<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\StoreTax\StoreTaxService;

/**
 * Class StoreTaxController
 * @package Biffy\Http\Controllers
 */
class StoreTaxController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var StoreTaxService
     */
    protected $service;

    /**
     * @param StoreTaxService $service
     */
    public function __construct(StoreTaxService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $storeId = \Auth::user()->storeId();

        return $this->data($this->service->findAllBy('store_id', $storeId))->respond();
    }
} 