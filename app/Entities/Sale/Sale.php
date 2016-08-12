<?php namespace Biffy\Entities\Sale;

use Biffy\Entities\AbstractEntity;

class Sale extends AbstractEntity
{
    protected $fillable = [
        'completed',
        'subtotal',
        'taxes',
        'payments',
        'trade_credit',
        'adjustments',
        'user_id',
        'store_id',
        'customer_id',
        'company_id',
        'quote_id',
        'invoice_id'
    ];

    public function setInvoiceIdAttribute($value)
    {
        $this->attributes['invoice_id'] = $value ?: null;
    }

    public function setQuoteIdAttribute($value)
    {
        $this->attributes['quote_id'] = $value ?: null;
    }

    public function company()
    {
        return $this->belongsTo('Biffy\Entities\Company\Company');
    }

    public function customer()
    {
        return $this->belongsTo('Biffy\Entities\Customer\Customer');
    }

    public function invoices()
    {
        //This isn't actually a one to many relationship, but we use a pivot table to associate sales with
        //  invoices
        return $this->belongsToMany('Biffy\Entities\Invoice\Invoice')
            ->withTimestamps();
    }

    public function quote()
    {
        return $this->belongsTo('Biffy\Entities\Quote\Quote');
    }

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }

    public function saleItems()
    {
        return $this->hasMany('Biffy\Entities\SaleItem\SaleItem');
    }

    public function salePayments()
    {
        return $this->hasMany('Biffy\Entities\SalePayment\SalePayment');
    }

    public function workOrders()
    {
        return $this->hasMany('Biffy\Entities\WorkOrder\WorkOrder');
    }

    public function workOrderNotes ()
    {
        return $this->hasManyThrough('Biffy\Entities\WorkOrderNote\WorkOrderNote', 'Biffy\Entities\WorkOrder\WorkOrder');
    }

    public function user()
    {
        return $this->belongsTo('Biffy\Entities\User\User');
    }
}