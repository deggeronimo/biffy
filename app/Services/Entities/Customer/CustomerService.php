<?php namespace Biffy\Services\Entities\Customer;

use Biffy\Entities\Customer\CustomerRepositoryInterface;
use Biffy\Services\Entities\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

/**
 * Class CustomerService
 * @package Biffy\Services\Entities\Customer
 */
class CustomerService extends Service
{
    /**
     * @param CustomerRepositoryInterface $repo
     */
    public function __construct(CustomerRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param int $count
     * @param int $page
     * @param mixed $filter
     * @param mixed $sorting
     *
     * @returns LengthAwarePaginator
     */
    public function getIndex($count, $page, $filter, $sorting)
    {
        return $this->inStore()->repo->paginate($count, $page)->filterBy($filter)->sortBy($sorting)->get();
    }

    /**
     * @param mixed $filter
     * @param mixed $sorting
     * @return array
     */
    public function getList($filter, $sorting)
    {
        $defaultCount = 20;

        $customerList = $this->inStore()->repo->filterBy($filter)->sortBy($sorting)->get()->take($defaultCount);

        $customerListCount = $customerList->count();

        if ($customerListCount < $defaultCount)
        {
            $this->repo->clearFilters()->clearQuery();

            $otherCustomerList = $this->notInStore()->repo->with(['store'])->filterBy($filter)->sortBy($sorting)->get()->take($defaultCount - $customerListCount);

            $customerList = $customerList->merge($otherCustomerList);
        }

        return $customerList;
    }

    /**
     * @param int|null $storeId
     * @return $this
     */
    private function notInStore($storeId = null)
    {
        if (is_null($storeId))
        {
            $storeId = Auth::user()->storeId();
        }

        $this->repo->addFilter('not_store_id', $storeId);

        return $this;
    }
}