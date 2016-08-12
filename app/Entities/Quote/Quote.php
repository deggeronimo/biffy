<?php namespace Biffy\Entities\Quote;

use Biffy\Entities\AbstractEntity;

class Quote extends AbstractEntity
{
    protected $fillable = [
        'customer_id',
        'subtotal',
        'taxes'
    ];

    public function sale()
    {
        return $this->hasOne('Biffy\Entities\Sale\Sale');
    }

    public function workOrders()
    {
        return $this->hasManyThrough('Biffy\Entities\WorkOrder\WorkOrder', 'Biffy\Entities\Sale\Sale');
    }
}