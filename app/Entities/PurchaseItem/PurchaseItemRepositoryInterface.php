<?php namespace Biffy\Entities\PurchaseItem;

use Biffy\Entities\AbstractRepositoryInterface;

/**
 * Interface PurchaseItemRepositoryInterface
 * @package Biffy\Entities\PurchaseItem
 */
interface PurchaseItemRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $purchaseOrderId
     * @param $storeItemId
     * @return mixed
     */
    public function getPurchaseItemBy($purchaseOrderId, $storeItemId);

    /**
     * @param $filterBy
     * @return mixed
     */
    public function getItemsWithPurchaseOrder($filterBy);
}
