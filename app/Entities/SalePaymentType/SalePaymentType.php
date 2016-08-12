<?php namespace Biffy\Entities\SalePaymentType;

use Biffy\Entities\AbstractEntity;

class SalePaymentType extends AbstractEntity
{
    const CASH = 1;
    const CREDIT = 2;
    const GIFT_CARD = 3;
    const ADJUSTMENT_AMOUNT = 4;
    const ADJUSTMENT_DISCOUNT = 5;
    const ADJUSTMENT_COMP = 6;
    const TRADE_CREDIT = 7;
    const CASH_REFUND = 8;
    const CREDIT_REFUND = 9;
    const CHECK = 10;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}