<?php namespace Biffy\Entities\InvoicePayment;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentInvoicePaymentRepository extends EloquentAbstractRepository implements InvoicePaymentRepositoryInterface
{
    public function __construct(InvoicePayment $model)
    {
        $this->model = $model;
    }
}