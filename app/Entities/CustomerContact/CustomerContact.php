<?php namespace Biffy\Entities\CustomerContact;

use Biffy\Entities\AbstractEntity;

/**
 * Class CustomerContact
 * @package Biffy\Entities\CustomerContact
 */
class CustomerContact extends AbstractEntity
{
    /**
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'type',
        'direction',
        'status',
        'content',
        'duration',
        'date',
        'callid'
    ];

    public $timestamps = false;

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo('Biffy\Entities\Customer\Customer');
    }
}