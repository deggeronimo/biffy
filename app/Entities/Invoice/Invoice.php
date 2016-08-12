<?php namespace Biffy\Entities\Invoice;

use Biffy\Entities\AbstractEntity;

class Invoice extends AbstractEntity
{
    protected $fillable = [
        'subtotal',
        'payments',
        'adjustments',
        'store_id',
        'customer_id',
        'company_id',
        'details'
    ];

    protected $appends = [
        'closed',
        'total_due'
    ];

    public function company()
    {
        return $this->belongsTo('Biffy\Entities\Company\Company');
    }

    public function customer()
    {
        return $this->belongsTo('Biffy\Entities\Customer\Customer');
    }

    public function getClosedAttribute()
    {
        return $this->subtotal == $this->payments;
    }

    public function getTotalDueAttribute()
    {
        return $this->subtotal - $this->payments - $this->adjustments;
    }

    public function invoicePayments()
    {
        return $this->hasMany('Biffy\Entities\InvoicePayment\InvoicePayment');
    }

    public function sales()
    {
        //This isn't actually a one to many relationship, but we use a pivot table to associate sales with
        //  invoices
        return $this->belongsToMany('Biffy\Entities\Sale\Sale')
            ->withPivot([])
            ->withTimestamps();
    }
}
