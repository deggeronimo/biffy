<?php namespace Biffy\Services\Entities\SalePayment;

use Biffy\Entities\SalePayment\SalePaymentRepositoryInterface;
use Biffy\Services\Entities\Service;

/**
 * Class SalePaymentService
 * @package Biffy\Services\Entities\SalePayment
 */
class SalePaymentService extends Service
{
    /**
     * @param SalePaymentRepositoryInterface $repo
     */
    public function __construct(SalePaymentRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}