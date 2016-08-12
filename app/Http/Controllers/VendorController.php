<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Vendor\VendorService;
use Illuminate\Support\Facades\Auth;

/**
 * Class VendorController
 * @package Biffy\Http\Controllers
 */
class VendorController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var VendorService
     */
    protected $service;

    /**
     * @param VendorService $service
     */
    function __construct(VendorService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $storeId = Auth::user()->storeId();

        $result = $this->service->getVendors($storeId);

        return $this->data($result)->respond();
    }
}