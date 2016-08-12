<?php namespace Biffy\Services\Entities\Vendor;

use Biffy\Entities\Vendor\VendorRepositoryInterface;
use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\Service;

/**
 * Class VendorService
 * @package Biffy\Services\Entities\Vendor
 */
class VendorService extends Service
{
    /**
     * @param VendorRepositoryInterface $repo
     */
    public function __construct(VendorRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param int id
     *
     * @return array
     */
    public function show($id)
    {
        return $this->repo->show($id)->toArray();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function store($attributes)
    {
        return $this->repo->create($attributes);
    }

    /**
     * @param int $id
     * @param array $attributes
     *
     * @return array
     */
    public function update($id, $attributes)
    {
        return $this->repo->update($id, $attributes);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function destroy($id)
    {
        $this->repo->delete($id);

        return [];
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getVendors($storeId)
    {
        return $this->repo->getVendors($storeId)->toArray();
    }
}