<?php namespace Biffy\Services\Entities\Company;

use Biffy\Services\Entities\Service;
use Biffy\Entities\Company\CompanyRepositoryInterface;

/**
 * Class CompanyService
 * @package Biffy\Services\Entities\Company
 */
class CompanyService extends Service
{
    /**
     * @param CompanyRepositoryInterface $repo
     */
    public function __construct(CompanyRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}