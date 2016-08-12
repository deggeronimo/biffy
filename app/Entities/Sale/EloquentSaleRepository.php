<?php namespace Biffy\Entities\Sale;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentSaleRepository
 * @package Biffy\Entities\Sale
 */
class EloquentSaleRepository extends EloquentAbstractRepository implements SaleRepositoryInterface
{
    /**
     * @var array
     */
    protected $filters = [
        'company_invoicing' => ['company_id = ? AND trade_credit > 0 AND invoice_id IS NULL', ':value'],
        'customer_invoicing' => ['customer_id = ? AND trade_credit > 0 AND invoice_id IS NULL', ':value']
    ];

    /**
     * @param Sale $model
     */
    public function __construct(Sale $model)
    {
        $this->model = $model;

        $this->with(['saleItems', 'saleItems.taxes', 'saleItems.taxes.storeTax', 'saleItems.inventory',
            'saleItems.inventory.storeItem', 'saleItems.inventory.storeItem.item', 'salePayments',
            'salePayments.salePaymentType', 'store', 'store.storeTax', 'workOrders', 'workOrders.assignedToUser',
            'workOrders.workOrderStatus', 'workOrders.device', 'workOrders.device.deviceType', 'workOrders.saleItems',
            'workOrders.saleItems.taxes', 'workOrders.saleItems.taxes.storeTax', 'workOrders.saleItems.inventory',
            'workOrders.saleItems.inventory.storeItem', 'workOrders.saleItems.inventory.storeItem.item',
            'workOrders.workOrderNotes' => function($query)
            {
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
            },
            'workOrders.workOrderNotes.user', 'workOrders.workOrderNotes.workorderStatus',
            'customer', 'customer.devices', 'customer.devices.deviceType', 'company', 'company.companyInstructions', 'invoices', 'quote', 'user'
        ]);
    }

    /**
     * @param $saleId
     * @return mixed
     */
    public function getStoreId($saleId)
    {
        return $this->find($saleId)->store_id;
    }

    public function create($attributes)
    {
        return parent::create($attributes);
    }

    /**
     * @param $count
     * @param $page
     * @return mixed
     */
    public function getSales($count, $page)
    {
        $result = $this->make()
            ->with(['customer'])
            ->orderBy('created_at', 'DESC')->paginate($count);

        return $result;
    }

    public function preGet()
    {
    }
}