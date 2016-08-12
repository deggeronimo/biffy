<?php namespace Biffy\Entities\Store;

use Biffy\Entities\AbstractEntity;
use Biffy\Entities\User\User;

class Store extends AbstractEntity
{
    protected $fillable = [
        'name',
        'group_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function accountExpenses()
    {
        return $this->hasMany('Biffy\Entities\AccountExpense\AccountExpense');
    }

    public function appointments()
    {
        return $this->hasMany('Biffy\Entities\Appointment\Appointment');
    }

    public function appointmentBlackouts()
    {
        return $this->hasMany('Biffy\Entities\AppointmentBlackout\AppointmentBlackout');
    }

    public function config()
    {
        return $this->hasMany('Biffy\Entities\StoreConfig\StoreConfig');
    }

    public function customers()
    {
        return $this->hasMany('Biffy\Entities\Customer\Customer');
    }

    public function feedbackDocs()
    {
        return $this->hasMany('Biffy\Entities\FeedbackDoc\FeedbackDoc');
    }

    public function group()
    {
        return $this->belongsTo('Biffy\Entities\Group\Group');
    }

    public function items()
    {
        return $this->belongsToMany('Biffy\Entities\Item\Item', 'store_items')
            ->withPivot(['stock', 'on_order', 'last_cost', 'unit_price', 'labor_cost'])
            ->withTimestamps();
    }

    public function purchaseOrders()
    {
        return $this->hasMany('Biffy\Entities\PurchaseOrder\PurchaseOrder');
    }

    public function sale()
    {
        return $this->hasMany('Biffy\Entities\Sale\Sale');
    }

    public function storeItems()
    {
        return $this->hasMany ( 'Biffy\Entities\StoreItem\StoreItem');
    }

    public function storeTax()
    {
        return $this->hasMany('Biffy\Entities\StoreTax\StoreTax');
    }

    public function timeClock()
    {
        return $this->hasMany('Biffy\Entities\TimeClockItem\TimeClockItem');
    }

    public function vendor()
    {
        return $this->hasMany('Biffy\Entities\Vendor\Vendor');
    }

    public function inventory ()
    {
        return $this->hasManyThrough('Biffy\Entities\Inventory\Inventory', 'Biffy\Entities\StoreItem\StoreItem');
    }

    public function workOrders()
    {
        return $this->hasManyThrough('Biffy\Entities\WorkOrder\WorkOrder', 'Biffy\Entities\Sale\Sale');
    }

    // This is not a relationship but query
    public function users()
    {
        return User::join('group_user', 'group_user.user_id', '=', 'users.id')
            ->join('groups', 'group_user.group_id', '=', 'groups.id')
            ->join('stores', 'stores.group_id', '=', 'groups.id')
            ->where('stores.id', $this->id)
            ->select('users.*')
            ->get();
    }

}
