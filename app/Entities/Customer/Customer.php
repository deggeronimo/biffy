<?php namespace Biffy\Entities\Customer;

use Biffy\Entities\AbstractEntity;

class Customer extends AbstractEntity
{
    protected $fillable = [
        'given_name',
        'family_name',
        'full_name',
        'phone',
        'email',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'referral_source',
        'store_id'
    ];

    protected $appends = ['store_name'];

    public function appointments()
    {
        return $this->hasMany('Biffy\Entities\Appointment\Appointment');
    }

    public function customerNotes()
    {
        return $this->hasMany('Biffy\Entities\CustomerNote\CustomerNote');
    }

    public function devices()
    {
        return $this->hasMany('Biffy\Entities\Device\Device');
    }

    public function feedbacks()
    {
        return $this->hasMany('Biffy\Entities\Feedback\Feedback');
    }

    public function quotes()
    {
        return $this->hasMany('Biffy\Entities\Quote\Quote');
    }

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }

    public function getStoreNameAttribute()
    {
        return $this->store->name;
    }

    public function workOrders()
    {
        return $this->hasManyThrough('Biffy\Entities\WorkOrder\WorkOrder', 'Biffy\Entities\Device\Device');
    }

    public function sales()
    {
        return $this->hasMany('Biffy\Entities\Sale\Sale');
    }
} 