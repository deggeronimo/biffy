<?php namespace Biffy\Entities\Customer;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentCustomerRepository
 * @package Biffy\Entities\Customer
 */
class EloquentCustomerRepository extends EloquentAbstractRepository implements CustomerRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'email' => [],
        'given_name' => [],
        'family_name' => [],
        'postal_code' => [],
        'referral_source' => []
    ];

    /**
     * @var array
     */
    protected $filters = [
        'email' => ['email LIKE ?', '%:value%'],
        'search' => ['email LIKE ? OR full_name LIKE ? OR id = ? OR phone LIKE ?', '%:value%', '%:value%', ':value', '%:value%'],
        'store_id' => ['store_id = ?', ':value'],
        'not_store_id' => ['store_id != ?', ':value']
    ];

    /**
     * @param Customer $model
     */
    public function __construct(Customer $model)
    {
        $this->model = $model;

        $this->with([ 'appointments', 'customerNotes', 'devices', 'devices.deviceType', 'feedbacks', 'workOrders',
            'workOrders.workOrderStatus', 'quotes', 'quotes.sale' ]);
    }
}