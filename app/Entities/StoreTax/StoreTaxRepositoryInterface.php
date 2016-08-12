<?php namespace Biffy\Entities\StoreTax;

use Biffy\Entities\AbstractRepositoryInterface;

/**
 * Interface StoreTaxRepositoryInterface
 * @package Biffy\Entities\StoreTax
 */
interface StoreTaxRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $storeId
     * @return mixed
     */
    public function getTaxIds($storeId);
}