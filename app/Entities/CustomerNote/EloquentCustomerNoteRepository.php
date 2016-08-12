<?php namespace Biffy\Entities\CustomerNote;

use Biffy\Entities\Customer\Customer;
use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentCustomerNoteRepository
 * @package Biffy\Entities\CustomerNote
 */
class EloquentCustomerNoteRepository extends EloquentAbstractRepository implements CustomerNoteRepositoryInterface
{
    /**
     * @var Customer
     */
    private $customerModel;

    /**
     * @param CustomerNote $model
     * @param Customer $customerModel
     */
    public function __construct(CustomerNote $model, Customer $customerModel)
    {
        $this->model = $model;
        $this->customerModel = $customerModel;
    }

    /**
     * @param $customerId
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getNotesForCustomer($customerId)
    {
        return $this->customerModel->find($customerId)->customerNotes()->get();
    }
}