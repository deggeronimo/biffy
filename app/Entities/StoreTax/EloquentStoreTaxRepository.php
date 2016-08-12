<?php namespace Biffy\Entities\StoreTax;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentStoreTaxRepository
 * @package Biffy\Entities\StoreTax
 */
class EloquentStoreTaxRepository extends EloquentAbstractRepository implements StoreTaxRepositoryInterface
{
    /**
     * @param StoreTax $model
     */
    public function __construct(StoreTax $model)
    {
        $this->model = $model;
    }

    /**
     * @param $storeId
     * @return array
     */
    public function getTaxIds($storeId)
    {
        return $this->findAllBy('store_id', $storeId);
    }
}