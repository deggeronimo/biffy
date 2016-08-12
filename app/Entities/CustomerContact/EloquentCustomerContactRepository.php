<?php namespace Biffy\Entities\CustomerContact;

use Biffy\Entities\Customer\Customer;
use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentCustomerContactRepository
 * @package Biffy\Entities\CustomerContact
 */
class EloquentCustomerContactRepository extends EloquentAbstractRepository implements CustomerContactRepositoryInterface
{
    /**
     * @var Customer
     */
    private $customerModel;

    /**
     * @param CustomerContact $model
     * @param Customer $customerModel
     */
    public function __construct(CustomerContact $model, Customer $customerModel)
    {
        $this->model = $model;
        $this->customerModel = $customerModel;
    }

    /**
     * @param $customerId
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getContactssForCustomer($customerId)
    {
        return $this->customerModel->find($customerId)->customerContacts()->get();
    }
}