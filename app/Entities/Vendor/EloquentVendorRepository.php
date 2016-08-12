<?php namespace Biffy\Entities\Vendor;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentVendorRepository
 * @package Biffy\Entities\Vendor
 */
class EloquentVendorRepository extends EloquentAbstractRepository implements VendorRepositoryInterface
{
    /**
     * @param Vendor $model
     */
    public function __construct(Vendor $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getVendors($storeId)
    {
        $query = $this->make();

        $query->where('store_id', '=', $storeId)->orWhere('global', '=', 1);

        return $query->get();
    }
}