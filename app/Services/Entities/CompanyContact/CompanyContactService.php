<?php namespace Biffy\Services\Entities\CompanyContact;

use Biffy\Entities\CompanyContact\CompanyContactRepositoryInterface;
use Biffy\Services\Entities\Service;

/**
 * Class CustomerService
 * @package Biffy\Services\Entities\Customer
 */
class CompanyContactService extends Service
{
    /**
     * @param CompanyContactRepositoryInterface $repo
     */
    public function __construct(CompanyContactRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}