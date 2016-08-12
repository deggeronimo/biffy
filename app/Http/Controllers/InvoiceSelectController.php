<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Invoice\InvoiceService;

class InvoiceSelectController extends ApiController
{
    /**
     * @var InvoiceService
     */
    public $service;

    /**
     * @param InvoiceService $userService
     */
    function __construct(InvoiceService $userService)
    {
        $this->service = $userService;
    }

    function payments($invoiceId)
    {
        return $this->data($this->service->invoicePayments($invoiceId)->toArray())->respond();
    }

    function sales($invoiceId)
    {
        return $this->data($this->service->invoiceSales($invoiceId)->toArray())->respond();
    }
}
