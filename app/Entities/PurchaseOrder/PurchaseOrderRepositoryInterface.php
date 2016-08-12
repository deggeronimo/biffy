<?php namespace Biffy\Entities\PurchaseOrder;

use Biffy\Entities\AbstractRepositoryInterface;

/**
 * Interface PurchaseOrderRepositoryInterface
 * @package Biffy\Entities\PurchaseOrder
 */
interface PurchaseOrderRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $storeId
     * @return mixed
     */
    public function getAllPurchaseOrders($storeId);
}