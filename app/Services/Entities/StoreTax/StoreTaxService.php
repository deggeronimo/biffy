<?php namespace Biffy\Services\Entities\StoreTax;

use Biffy\Entities\StoreTax\StoreTaxRepositoryInterface;
use Biffy\Services\Entities\Service;

/**
 * Class StoreTaxService
 * @package Biffy\Services\Entities\StoreTax
 */
class StoreTaxService extends Service
{
    /**
     * @param StoreTaxRepositoryInterface $repo
     */
    public function __construct(StoreTaxRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function getTaxIds($storeId)
    {
        return $this->repo->getTaxIds($storeId);
    }
}