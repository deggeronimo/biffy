<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\SalePayment\SalePaymentService;

/**
 * Class SalePaymentController
 * @package Biffy\Http\Controllers
 */
class SalePaymentController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var SalePaymentService
     */
    private $service;

    /**
     * @param SalePaymentService $service
     */
    public function __construct(SalePaymentService $service)
    {
        $this->service = $service;
    }
}