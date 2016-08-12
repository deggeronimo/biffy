<?php namespace Biffy\Entities\WorkOrder;

use Biffy\Entities\Customer\Customer;
use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Entities\Store\Store;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EloquentWorkOrderRepository
 * @package Biffy\Entities\WorkOrder
 */
class EloquentWorkOrderRepository extends EloquentAbstractRepository implements WorkOrderRepositoryInterface
{
    protected $filters = [
        'update_today' => [ 'date(next_update) = date(\'now\')' ],
        'single_day' => [ 'date(next_update) = date(created_at)' ],
        'multi_day' => [ 'date(next_update) > date(created_at)' ],
        'contact_list' => [ 'workorder_status_id = 4 or workorder_status_id = 5 or workorder_status_id = 9' ],
        'parts_list' => [ 'workorder_status_id = 2 or workorder_status_id = 6'],

        'repair_queue' => [ 'workorder_status_id = 3' ],
        'awaiting_parts' => [ 'workorder_status_id = 2' ],
        'needs_to_order_parts' => [ 'workorder_status_id = 6' ],
        'awaiting_approval' => [ 'workorder_status_id = 12' ],

        'search' => ['sale_id LIKE ? OR workorder_status_id LIKE ? OR created_at LIKE ? OR next_update LIKE ?', '%:value%', '%:value%', '%:value%','%:value%']
    ];

    protected $sorters = [
        'sale_id' => [],
        'next_update' => [],
        'workorder_status_id' => [],
        'created_at' => [],
        'updated_at' => [],
    ];

    /**
     * @var Customer $customerModel
     */
    private $customerModel;

    /**
     * @var Store $storeModel
     */
    private $storeModel;

    /**
     * @param WorkOrder $model
     * @param Customer $customerModel
     * @param Store $storeModel
     */
    public function __construct(WorkOrder $model, Customer $customerModel, Store $storeModel)
    {
        $this->model = $model;
        $this->customerModel = $customerModel;
        $this->storeModel = $storeModel;

        $this->with([ 'assignedToUser', 'device', 'device.deviceType', 'saleItems.inventory.storeItem.item', 'sale',
            'sale.customer', 'sale.invoices',
            'workorderNotes' => function ($query)
            {
                $query->orderBy('created_at', 'desc');
            },
            'workorderNotes.user', 'workorderNotes.workorderStatus' ]);
    }

    /**
     * @param int $storeId
     * @param int $count
     * @param $workorderStatus
     * @return Collection
     */
    public function getPaginatedWorkOrderIndexForStoreWithStatus($storeId, $count, $workorderStatus)
    {
        $store = $this->storeModel->find($storeId);

        if (is_null($store))
        {
            return new Collection([ 'data' => [] ]);
        }
        else
        {
            return $store->workOrders()->with([ 'sale', 'sale.customer', 'device', 'workorderStatus' ])
                ->where('workorder_status_id', '=', $workorderStatus)->paginate($count, [ 'workorders.*' ]);
        }
    }

    /**
     * @param $storeId
     * @param $count
     * @param $workorderStatus
     * @param $filterBy
     * @return mixed
     */
    public function getFilteredPaginatedWorkOrderIndexForStoreWithStatus($storeId, $count, $workorderStatus, $filterBy)
    {
        $store = $this->storeModel->find($storeId);

        if ( is_null($store))
        {
            return new Collection([ 'data' => [] ]);
        }
        else
        {
            $query = $store->workOrders()->with([ 'sale', 'sale.customer', 'device', 'workorderStatus' ]);

            if (!is_null($filterBy))
            {
                foreach ($filterBy as $key => $value)
                {
                    if (strlen($value) >= 3)
                    {
                        $query->join('customers', 'customers.family_name', 'like', '%' . $value . '%');
                    }
                }
            }

            return $query->paginate($count, [ 'workorders.*' ]);
        }
    }

    /**
     * @param int $storeId
     * @param $count
     * @return mixed
     */
    public function getPaginatedWorkOrderIndexForStore($storeId, $count)
    {
        $store = $this->storeModel->find($storeId);

        if ( is_null($store))
        {
            return new Collection([ 'data' => [] ]);
        }
        else
        {
            return  $store->workOrders()->with([ 'sale', 'sale.customer', 'device', 'workorderStatus' ])
                ->paginate($count, [ 'workorders.*' ]);
        }
    }
}