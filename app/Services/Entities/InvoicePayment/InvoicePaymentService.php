<?php namespace Biffy\Services\Entities\InvoicePayment;

use Biffy\Entities\InvoicePayment\EloquentInvoicePaymentRepository;
use Biffy\Services\Entities\Service;

class InvoicePaymentService extends Service
{
    public function __construct(EloquentInvoicePaymentRepository $repo)
    {
        $this->repo = $repo;
    }
}