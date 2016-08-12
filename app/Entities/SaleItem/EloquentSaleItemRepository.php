<?php namespace Biffy\Entities\SaleItem;

use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Entities\Sale\Sale;
use Biffy\Entities\WorkOrder\WorkOrder;

/**
 * Class EloquentSaleItemRepository
 * @package Biffy\Entities\SaleItem
 */
class EloquentSaleItemRepository extends EloquentAbstractRepository implements SaleItemRepositoryInterface
{
    private $saleModel;
    private $workOrderModel;

    private $saleItemResult;

    /**
     * @param SaleItem $model
     * @param WorkOrder $workOrderModel
     * @param Sale $saleModel
     */
    public function __construct(SaleItem $model, WorkOrder $workOrderModel, Sale $saleModel)
    {
        $this->model = $model;
        $this->workOrderModel = $workOrderModel;
        $this->saleModel = $saleModel;

        $this->with([ 'taxes', 'taxes.storeTax', 'inventory', 'inventory.storeItem', 'inventory.storeItem.item' ]);
    }

    public function getAllWhereSaleCompleted($startDate, $endDate)
    {
        if (!$this->saleItemResult)
        {
            $saleItemQuery = $this->make();
            $this->saleItemResult = $saleItemQuery->with(['workOrder', 'workOrder.sale', 'sale'])
                ->where('created_at' >= $startDate)->where('created_at' < $endDate)->get(['work_order_id'])->toArray();
        }

        $result = [];

        foreach ($this->saleItemResult as $saleItem)
        {
            if ($saleItem->work_order->sale->completed == 1)
            {
                $result[] = $saleItem;
            }
        }

        return $result;
    }

    public function getAllWhereSaleNotCompleted($startDate, $endDate)
    {
        if (!$this->saleItemResult)
        {
            $saleItemQuery = $this->make();
            $this->saleItemResult = $saleItemQuery->with(['workOrder', 'workOrder.sale', 'sale'])
                ->where('created_at' >= $startDate)->where('created_at' < $endDate)->get(['work_order_id'])->toArray();
        }

        $result = [];

        foreach ($this->saleItemResult as $saleItem)
        {
            if ($saleItem->work_order->sale->completed == 0)
            {
                $result[] = $saleItem;
            }
        }

        return $result;
    }

    public function getAllForStoreBetweenDates($select, $storeId, $startDate, $endDate)
    {
        $query = $this->make();

        $query
            ->select($select)
            ->join('inventory', 'sale_items.inventory_id', '=', 'inventory.id')
            ->join('store_items', 'inventory.store_item_id', '=', 'store_items.id')
            ->join('items', 'store_items.item_id', '=', 'items.id')
            ->where('store_items.store_id', '=', $storeId)
            ->where('sale_items.created_at', '>=', $startDate)
            ->where('sale_items.created_at', '<', $endDate);

        return $query->get();
    }
}