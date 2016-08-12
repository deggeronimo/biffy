<?php namespace Biffy\Entities\StoreItem;

use Biffy\Entities\AbstractRepositoryInterface;

interface StoreItemRepositoryInterface extends AbstractRepositoryInterface
{
    public function doesItemIdExist($storeId, $itemId);

    public function getOne($storeId, $itemId);

    /**
     * @param $storeId
     * @return mixed
     */
    public function getAllStoreItems($storeId);

    /**
     * @param $storeId
     * @param $filter
     * @param $sorting
     * @param $count
     * @param $page
     * @return mixed
     */
    public function getStoreItems($storeId, $filter, $sorting, $count, $page);

    public function selectStoreItemTableFirst($filter, $sorting, $defaultCount);
} 