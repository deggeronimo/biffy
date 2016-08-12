<?php namespace Biffy\Entities\Sale;

use Biffy\Entities\AbstractRepositoryInterface;

/**
 * Interface SaleRepositoryInterface
 * @package Biffy\Entities\Sale
 */
interface SaleRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $saleId
     * @return mixed
     */
    public function getStoreId($saleId);

    /**
     * @param $count
     * @param $page
     * @return mixed
     */
    public function getSales($count, $page);
}