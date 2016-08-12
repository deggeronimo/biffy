<?php namespace Biffy\Services\Entities\SaleItemTax;

use Biffy\Entities\SaleItemTax\SaleItemTaxRepositoryInterface;
use Biffy\Services\Entities\Service;

/**
 * Class SaleItemTaxService
 * @package Biffy\Services\Entities\SaleItemTax
 */
class SaleItemTaxService extends Service
{
    /**
     * @param SaleItemTaxRepositoryInterface $repo
     */
    public function __construct(SaleItemTaxRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}
