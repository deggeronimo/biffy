<?php namespace Biffy\Entities\InvoicePayment;

use Biffy\Entities\AbstractEntity;

class InvoicePayment extends AbstractEntity
{
    protected $fillable = [
        'invoice_id',
        'sale_payment_type_id',
        'amount',
        'meta_data'
    ];
}