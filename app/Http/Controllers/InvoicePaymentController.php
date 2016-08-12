<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\InvoicePayment\InvoicePaymentService;

class InvoicePaymentController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(InvoicePaymentService $service)
    {
        $this->service = $service;
    }
}