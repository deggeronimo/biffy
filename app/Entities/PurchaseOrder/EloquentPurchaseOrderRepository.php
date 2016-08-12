<?php namespace Biffy\Entities\PurchaseOrder;

use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Entities\Store\Store;

/**
 * Class EloquentPurchaseOrderRepository
 * @package Biffy\Entities\PurchaseOrder
 */
class EloquentPurchaseOrderRepository extends EloquentAbstractRepository implements PurchaseOrderRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'created_at' => [],
        'currency_rate' => [],
        'subtotal' => [],
        'taxes' => []
    ];

    /**
     * @var array
     */
    protected $filters = [
        'store_id' => [ 'store_id = ?', ':value' ],
    ];

    /**
     * @var Store
     */
    private $storeModel;

    /**
     * @param PurchaseOrder $model
     * @param Store $storeModel
     */
    public function __construct(PurchaseOrder $model, Store $storeModel)
    {
        $this->model = $model;
        $this->storeModel = $storeModel;

        $this->with([ 'purchaseItems', 'purchaseItems.storeItem', 'purchaseItems.storeItem.item',
            'purchaseItems.receiveItems', 'vendor',
//            'purchaseItems.backOrderItems' => function($query)
//            {
//                $query->leftJoin('inventory', 'inventory.store_item_id', '=', 'store_items.id');
//                $query->leftJoin('sale_items', 'sale_items.inventory_id', '=', 'inventory.id');
//                $query->where('store_items.stock', '<', '0');
//                $query->select([ 'sale_items.id', 'sale_items.work_order_id' ]);
//            }
        ]);
    }

    public function getAllPurchaseOrders($storeId)
    {
        $store = $this->storeModel->find($storeId);

        return $store->purchaseOrders()->orderBy('created_at', 'desc')->get();
    }

    public function find($id)
    {
//        dd($this->with);
        return parent::find($id);
    }
}
