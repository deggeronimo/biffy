<?php namespace Biffy\Entities\Vendor;

use Biffy\Entities\AbstractEntity;

class Vendor extends AbstractEntity
{
    protected $table = 'vendors';

    protected $fillable = [
        'name',
        'account_number',
        'contact_name',
        'contact_phone',
        'global',
        'store_id'
    ];

    public $timestamps = false;

    public function accountExpense()
    {
        return $this->belongsTo('Biffy\Entities\AccountExpense\AccountExpense');
    }

    public function inventory()
    {
        $this->hasMany('Biffy\Entities\Inventory\Inventory');
    }

    public function purchaseOrder()
    {
        return $this->hasMany('Biffy\Entities\PurchaseOrder\PurchaseOrder');
    }

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }
}