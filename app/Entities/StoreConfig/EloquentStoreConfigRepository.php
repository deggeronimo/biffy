<?php namespace Biffy\Entities\StoreConfig;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentStoreConfigRepository
 * @package Biffy\Entities\StoreConfig
 */
class EloquentStoreConfigRepository extends EloquentAbstractRepository implements StoreConfigRepositoryInterface
{
    /**
     * @param StoreConfig $model
     */
    public function __construct(StoreConfig $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $storeId
     * @param int $configId
     * @return mixed
     */
    public function getEntry($storeId, $configId)
    {
        return $this->make()->where('store_id', '=', $storeId)->where('config_id', '=', $configId)->first();
    }

    public function getEntries($storeId)
    {
        return $this->make()->where('store_id', '=', $storeId)->get();
    }
}