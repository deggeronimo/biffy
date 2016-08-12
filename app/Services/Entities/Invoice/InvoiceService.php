<?php namespace Biffy\Services\Entities\Invoice;

use Biffy\Entities\Invoice\InvoiceRepositoryInterface;
use Biffy\Services\Entities\Service;

class InvoiceService extends Service
{
    public function __construct(InvoiceRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function invoicePayments($invoiceId)
    {
        $result = $this->repo->find($invoiceId);

        return $result->payments;
    }

    public function invoiceSales($invoiceId)
    {
        $result = $this->repo->find($invoiceId);

        return $result->sales;
    }
}