<?php namespace Biffy\Entities\ReceiveItem;

use Biffy\Entities\AbstractRepositoryInterface;

/**
 * Interface ReceiveItemRepositoryInterface
 * @package Biffy\Entities\ReceiveItem
 */
interface ReceiveItemRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $purchaseItemId
     * @return mixed
     */
    public function getReceiveItemsForPurchaseItem($purchaseItemId);

    /**
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getReceiveItemsForPurchaseOrder($purchaseOrderId);
}