<?php namespace Biffy\Entities\SalePayment;

use Biffy\Entities\AbstractEntity;

/**
 * Class SalePayment
 * @package Biffy\Entities\SalePayment
 */
class SalePayment extends AbstractEntity
{
    /**
     *
     */
    const PAYMENTTYPE_CASH = 1;
    /**
     *
     */
    const PAYMENTTYPE_CREDIT = 2;
    /**
     *
     */
    const PAYMENTTYPE_GIFTCARD = 3;

    /**
     * @var array
     */
    protected $fillable = [
        'sale_id',
        'sale_payment_type_id',
        'amount'
    ];

    /**
     * @return mixed
     */
    public function sale()
    {
        return $this->belongsTo('Biffy\Entities\Sale\Sale');
    }

    public function salePaymentType()
    {
        return $this->belongsTo('Biffy\Entities\SalePaymentType\SalePaymentType');
    }
}