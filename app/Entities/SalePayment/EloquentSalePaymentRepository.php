<?php namespace Biffy\Entities\SalePayment;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentSalePaymentRepository
 * @package Biffy\Entities\SalePayment
 */
class EloquentSalePaymentRepository extends EloquentAbstractRepository implements SalePaymentRepositoryInterface
{
    /**
     * @param SalePayment $model
     */
    public function __construct(SalePayment $model)
    {
        $this->model = $model;
    }
}