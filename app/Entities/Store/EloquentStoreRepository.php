<?php namespace Biffy\Entities\Store;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentStoreRepository
 * @package Biffy\Entities\Store
 */
class EloquentStoreRepository extends EloquentAbstractRepository implements StoreRepositoryInterface
{
    /**
     * @param Store $model
     */
    public function __construct(Store $model)
    {
        $this->model = $model;
    }

    /**
     * @param $storeId
     * @param array|int $itemIds
     * @return mixed
     */
    public function assignItems($storeId, $itemIds)
    {
        $this->find($storeId)->items()->attach($itemIds, ['quantity' => 0]);
    }

    /**
     * @param $storeId
     * @param $itemId
     * @param $quantity
     */
    public function updateStoreItem($storeId, $itemId, $quantity)
    {
        $this->find($storeId)->items()->updateExistingPivot($itemId, ['quantity' => $quantity]);
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function getItems($storeId)
    {
        return $this->find($storeId)->items;
    }

    /**
     * @return array
     */
    public function getAllIds()
    {
        return $this->all()->modelKeys();
    }
}