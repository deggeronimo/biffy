<?php namespace Biffy\Services\Entities\CompanyStoreItem;

use Biffy\Entities\CompanyStoreItem\CompanyStoreItemRepositoryInterface;
use Biffy\Services\Entities\Service;

class CompanyStoreItemService extends Service
{
    /**
     * @param CompanyStoreItemRepositoryInterface $repo
     */
    public function __construct(CompanyStoreItemRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}