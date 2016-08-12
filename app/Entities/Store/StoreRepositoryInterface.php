<?php namespace Biffy\Entities\Store;

use Biffy\Entities\AbstractRepositoryInterface;

interface StoreRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $storeId
     * @param array|int $itemIds
     * @return mixed
     */
    public function assignItems($storeId, $itemIds);

    public function updateStoreItem($storeId, $itemId, $quantity);

    public function getItems($storeId);

    public function getAllIds();
} 