<?php namespace Biffy\Entities\Inventory;

use Biffy\Entities\AbstractRepositoryInterface;

interface InventoryRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param int $storeId
     * @return mixed
     */
    public function getInventoryForStore($storeId);

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getInventoryCountsForStore($storeId);

    /**
     * @param int $itemId
     * @return mixed
     */
    public function getInventoryForItem($itemId);

    /**
     * @param int $itemId
     * @return mixed
     */
    public function getInventoryCountForItem($itemId);

    /**
     * @param int $id
     * @return mixed
     */
    public function getInventoryById($id);
}