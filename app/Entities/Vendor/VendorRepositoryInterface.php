<?php namespace Biffy\Entities\Vendor;

use Biffy\Entities\AbstractRepositoryInterface;

interface VendorRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param int $storeId
     * @return mixed
     */
    public function getVendors ( $storeId );
}