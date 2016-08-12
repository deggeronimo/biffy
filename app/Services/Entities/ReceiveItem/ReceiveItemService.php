<?php namespace Biffy\Services\Entities\ReceiveItem;

use Biffy\Entities\Inventory\Inventory;
use Biffy\Entities\Inventory\InventoryRepositoryInterface;
use Biffy\Entities\PurchaseItem\PurchaseItemRepositoryInterface;
use Biffy\Entities\ReceiveItem\ReceiveItemRepositoryInterface;
use Biffy\Entities\StoreItem\StoreItemRepositoryInterface;
use Biffy\Services\Entities\Inventory\InventoryService;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * Class ReceiveItemService
 * @package Biffy\Services\Entities\ReceiveItem
 */
class ReceiveItemService extends Service
{
    protected $inventoryRepo;
    protected $purchaseItemRepo;
    protected $storeItemRepo;

    protected $inventoryService;
    protected $workOrderService;

    /**
     * @param ReceiveItemRepositoryInterface $repo
     * @param PurchaseItemRepositoryInterface $purchaseItemRepo
     * @param InventoryRepositoryInterface $inventoryRepo
     * @param StoreItemRepositoryInterface $storeItemRepo
     * @param InventoryService $inventoryService
     * @param WorkOrderService $workOrderService
     */
    public function __construct(ReceiveItemRepositoryInterface $repo, PurchaseItemRepositoryInterface $purchaseItemRepo,
        InventoryRepositoryInterface $inventoryRepo, StoreItemRepositoryInterface $storeItemRepo, InventoryService $inventoryService,
        WorkOrderService $workOrderService)
    {
        $this->repo = $repo;
        $this->inventoryRepo = $inventoryRepo;
        $this->purchaseItemRepo = $purchaseItemRepo;
        $this->storeItemRepo = $storeItemRepo;

        $this->inventoryService = $inventoryService;
        $this->workOrderService = $workOrderService;
    }

    /**
     * @param $purchaseItemId
     * @return mixed
     */
    public function getReceiveItemsForPurchaseItem($purchaseItemId)
    {
        return $this->repo->getReceiveItemsForPurchaseItem($purchaseItemId);
    }

    /**
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getReceiveItemsForPurchaseOrder($purchaseOrderId)
    {
        return $this->repo->getReceiveItemsForPurchaseOrder($purchaseOrderId);
    }

    /**
     * @param $purchaseItemId
     * @param $quantity
     * @return static
     * @throws Exception
     */
    public function receivePurchaseItem($purchaseItemId, $quantity)
    {
        $purchaseItem = $this->purchaseItemRepo->with(['PurchaseOrder'])->find($purchaseItemId);

        $purchaseOrder = $purchaseItem->purchaseOrder;

        $purchaseItemList = $purchaseOrder->purchaseItems;
        $numberOfItems = 0;

        //TODO: Do this with one sql call
        foreach ($purchaseItemList as $item)
        {
            $numberOfItems += $item->quantity;

            $receiveItems = $item->receiveItems;

            foreach ($receiveItems as $receiveItem)
            {
                $numberOfItems += $receiveItem->quantity;
            }
        }

        $individualShippingCost = $purchaseOrder->shipping_cost / $numberOfItems;

        if ($quantity > $purchaseItem->quantity)
        {
            $quantity = $purchaseItem->quantity;
        }

        if ( $quantity <= 0 )
        {
            return [];
        }

        $returnValue = $this->repo->create([
            'purchase_item_id' => $purchaseItemId,
            'quantity' => $quantity
        ]);

        for ($i = 0; $i < $quantity; $i ++)
        {
            $inventoryItem = $this->inventoryService->reserveInventoryItemForReceive($purchaseItem->store_item_id);

            //TODO: Verify that this can happen
//            if (is_null($inventoryItem))
//            {
//                break;
//            }

            $inventoryItem->shipping_cost = $individualShippingCost;
            $inventoryItem->received_by_user_id = Auth::user()->userId();
            $inventoryItem->status = Inventory::STATUS_RECEIVED;
            $inventoryItem->save();

            if (!is_null($inventoryItem->sold_by_user_id))
            {
                $saleItem = $inventoryItem->saleItem;

                if (!is_null($saleItem) && !is_null($saleItem->work_order_id))
                {
                    $workOrder = $saleItem->workOrder;
                    $workOrderCache = $workOrder->workOrderCache;

                    $workOrderCache->awaiting_parts --;
                    $workOrderCache->save();

                    $this->workOrderService->updateStatus($workOrder, $workOrderCache);
                }
            }
        }

        $quantity = $i;

        $storeItem = $this->storeItemRepo->find($purchaseItem->store_item_id);
        $storeItem->stock += $quantity;
        $storeItem->on_order -= $quantity;
        $storeItem->last_cost = $purchaseItem->cost;
        $storeItem->save ();

        $purchaseItem->quantity -= $quantity;
        $purchaseItem->save();

        return $returnValue;
    }

    /**
     * @param int $purchaseItemId
     * @param int $id
     * @return array
     */
    public function unreceivePurchaseItem($purchaseItemId, $id)
    {
        $receiveItem = $this->repo->find($id);

        $purchaseItem = $receiveItem->purchaseItem;

        if ($purchaseItem->id != $purchaseItemId)
        {
            return [];
        }

        $purchaseItem->quantity += $receiveItem->quantity;
        $purchaseItem->save();

        $storeItem = $purchaseItem->storeItem;
        $storeItem->on_order += $receiveItem->quantity;
        //Removing Inventory items through the InventoryService will update the storeItem->stock
        $storeItem->save();

        for ($i = 0; $i < $receiveItem->quantity; $i ++)
        {
            if (!$this->inventoryService->unreserveInventoryItemForReceive($storeItem->id))
            {
                break;
            }
        }

        if ($i == $receiveItem->quantity)
        {
            $this->repo->delete($id);
        }
        else
        {
            $receiveItem->quantity -= $i;
            $receiveItem->save();
        }

        return [];
    }
}