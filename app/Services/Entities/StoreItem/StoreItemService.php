<?php namespace Biffy\Services\Entities\StoreItem;

use Biffy\Entities\StoreItem\StoreItemRepositoryInterface;
use Biffy\Services\Entities\Service;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class StoreItemService
 * @package Biffy\Services\Entities\StoreItem
 */
class StoreItemService extends Service
{
    /**
     * @var StoreItemRepositoryInterface
     */
    protected $repo;

    /**
     * @param StoreItemRepositoryInterface $repo
     */
    public function __construct(StoreItemRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function doesItemIdExist($storeId, $itemId)
    {
        return $this->repo->doesItemIdExist($storeId, $itemId);
    }

    public function getOne($storeId, $itemId)
    {
        return $this->repo->getOne($storeId, $itemId);
    }

    /**
     * @param $storeId
     * @param $filter
     * @return mixed
     */
    public function getAllStoreItems($storeId, $filter)
    {
        return $this->repo->getAllStoreItems($storeId, $filter);
    }

    /**
     * @param mixed $filter
     * @param mixed $sorting
     * @param $deviceTypeId
     * @param null $storeId
     * @return array
     */
    public function getList($filter, $sorting, $deviceTypeId = null, $storeId = null)
    {
        $defaultCount = is_null($deviceTypeId) ? 5 : 20;

        $storeItemList = $this->inDevice($deviceTypeId)->inStore($storeId)->repo->selectStoreItemTableFirst($filter, $sorting, $defaultCount);

        $storeItemListCount = $storeItemList->count();

        if ($storeItemListCount < $defaultCount)
        {
            $this->repo->clearFilters()->clearQuery();

            $otherStoreItemList = $this->notInDevice($deviceTypeId)->inStore($storeId)->repo->selectStoreItemTableFirst($filter, $sorting, $defaultCount - $storeItemListCount);;

            $storeItemList = $storeItemList->merge($otherStoreItemList);
        }

        // todo this is dumb
        if ($storeItemList instanceof LengthAwarePaginator) {
            $storeItemList = $storeItemList->getCollection();
        }

        return $storeItemList;
    }

    /**
     * @param $storeId
     * @param $filter
     * @param $sorting
     * @param $count
     * @param $page
     * @return mixed
     */
    public function getStoreItems($storeId, $filter, $sorting, $count, $page)
    {
        return $this->repo->getStoreItems($storeId, $filter, $sorting, $count, $page);
    }

    private function inDevice($deviceTypeId)
    {
        if (!is_null($deviceTypeId))
        {
            $this->repo->addFilter('device_type_id', $deviceTypeId);
        }

        return $this;
    }

    private function notInDevice($deviceTypeId = null)
    {
        if (!is_null($deviceTypeId))
        {
            $this->repo->addFilter('not_device_type_id', $deviceTypeId);
        }

        return $this;
    }

    public function handleNew($data)
    {
        foreach ($data as $d) {
            $this->create($d);
        }
    }
}