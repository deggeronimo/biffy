<?php namespace Biffy\Services\Entities\PurchaseOrder;

use Biffy\Entities\Inventory\Inventory;
use Biffy\Entities\PurchaseOrder\PurchaseOrderRepositoryInterface;
use Biffy\Entities\StoreItem\EloquentStoreItemRepository;
use Biffy\Services\Entities\Inventory\InventoryService;
use Biffy\Services\Entities\PurchaseItem\PurchaseItemService;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class PurchaseOrderService
 * @package Biffy\Services\Entities\PurchaseOrder
 */
class PurchaseOrderService extends Service
{
    protected $inventoryService;
    protected $purchaseItemService;
    protected $workOrderService;

    protected $storeItemRepository;

    /**
     * @param PurchaseOrderRepositoryInterface $repo
     * @param EloquentStoreItemRepository $storeItemRepository
     * @param InventoryService $inventoryService
     * @param PurchaseItemService $purchaseItemService
     * @param WorkOrderService $workOrderService
     */
    public function __construct(PurchaseOrderRepositoryInterface $repo, EloquentStoreItemRepository $storeItemRepository,
                                InventoryService $inventoryService, PurchaseItemService $purchaseItemService,
                                WorkOrderService $workOrderService)
    {
        $this->repo = $repo;
        $this->storeItemRepository = $storeItemRepository;

        $this->inventoryService = $inventoryService;
        $this->workOrderService = $workOrderService;
    }

    public function getAllPurchaseOrders($storeId)
    {
        return $this->repo->getAllPurchaseOrders($storeId)->toArray();
    }

    public function find($id)
    {
        $result = parent::find($id);

        $purchaseItems = $result->purchaseItems;

        foreach ($purchaseItems as & $purchaseItem)
        {
            $storeItem = $purchaseItem->storeItem;

            if ($storeItem->stock < 0)
            {
                $inventoryList = $storeItem->inventory;
                $backOrderList = [];

                foreach ($inventoryList as $inventory)
                {
                    $backOrderList[] = $inventory->saleItem;
                }

                $purchaseItem->back_ordered_items = new Collection($backOrderList);
            }
        }

        $result->purchase_items = $purchaseItems;

        return $result;
    }

    public function finalizePurchaseOrder($purchaseOrderId)
    {
        $purchaseOrder = $this->repo->find($purchaseOrderId);

        $purchaseOrder->subtotal = 0;

        $purchaseItemList = $purchaseOrder->purchaseItems;

        foreach ($purchaseItemList as $purchaseItem)
        {
            $storeItem = $purchaseItem->storeItem;
            $storeItem->on_order += $purchaseItem->quantity;
            $storeItem->save();

            for ($i = 0; $i < $purchaseItem->quantity; $i ++)
            {
                $inventoryItem = $this->inventoryService->reserveInventoryItemForPurchase($storeItem->id);

                $inventoryItem->cost = $purchaseItem->cost;
                $inventoryItem->purchased_by_user_id = Auth::user()->userId();
                $inventoryItem->status = Inventory::STATUS_PURCHASED;
                $inventoryItem->vendor_id = $purchaseOrder->vendor_id;
                $inventoryItem->save();

                if (!is_null($inventoryItem->sold_by_user_id))
                {
                    $saleItem = $inventoryItem->saleItem;

                    if (!is_null($saleItem) && !is_null($saleItem->workOrder))
                    {
                        $workOrder = $saleItem->workOrder;
                        $workOrderCache = $workOrder->workOrderCache;

                        $workOrderCache->needs_to_order_parts --;
                        $workOrderCache->awaiting_parts ++;
                        $workOrderCache->save();

                        $this->workOrderService->updateStatus($workOrder, $workOrderCache);
                    }
                }
            }

            $purchaseOrder->subtotal += $purchaseItem->cost * $purchaseItem->quantity;
        }

        $purchaseOrder->taxes = $purchaseOrder->subtotal * 0.065;
        $purchaseOrder->save();

        return $purchaseOrder;
    }
}