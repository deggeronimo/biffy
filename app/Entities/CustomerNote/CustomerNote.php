<?php namespace Biffy\Entities\CustomerNote;

use Biffy\Entities\AbstractEntity;

/**
 * Class CustomerNote
 * @package Biffy\Entities\CustomerNote
 */
class CustomerNote extends AbstractEntity
{
    /**
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'note'
    ];

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo('Biffy\Entities\Customer\Customer');
    }
}