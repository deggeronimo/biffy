<?php namespace Biffy\Services\Entities\SaleItem;

use Biffy\Entities\Inventory\Inventory;
use Biffy\Entities\SaleItem\SaleItemRepositoryInterface;
use Biffy\Entities\SaleItemTax\SaleItemTaxRepositoryInterface;
use Biffy\Entities\StoreItem\StoreItemRepositoryInterface;
use Biffy\Entities\StoreTax\StoreTaxRepositoryInterface;
use Biffy\Services\Entities\Inventory\InventoryService;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;
use Biffy\Services\Entities\WorkOrderNote\WorkOrderNoteService;

/**
 * Class SaleItemService
 * @package Biffy\Services\Entities\SaleItem
 */
class SaleItemService extends Service
{
    protected $saleItemTaxRepo;
    protected $storeTaxRepo;
    protected $inventoryService;

    /**
     * @param SaleItemRepositoryInterface $repo
     * @param SaleItemTaxRepositoryInterface $saleItemTaxRepo
     * @param StoreItemRepositoryInterface $storeItemRepo
     * @param StoreTaxRepositoryInterface $storeTaxRepo
     * @param InventoryService $inventoryService
     * @param WorkOrderService $workOrderService
     * @param WorkOrderNoteService $workOrderNoteService
     */
    public function __construct(SaleItemRepositoryInterface $repo, SaleItemTaxRepositoryInterface $saleItemTaxRepo,
                                StoreItemRepositoryInterface $storeItemRepo, StoreTaxRepositoryInterface $storeTaxRepo,
                                InventoryService $inventoryService, WorkOrderService $workOrderService, WorkOrderNoteService $workOrderNoteService)
    {
        $this->repo = $repo;
        $this->saleItemTaxRepo = $saleItemTaxRepo;
        $this->storeItemRepo = $storeItemRepo;
        $this->storeTaxRepo = $storeTaxRepo;

        $this->inventoryService = $inventoryService;
        $this->workOrderService = $workOrderService;
        $this->workOrderNoteService = $workOrderNoteService;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes)
    {
        \DB::beginTransaction();

        $storeItemId = $attributes['store_item_id'];
        $inventoryItem = $this->inventoryService->reserveInventoryItemForSale($storeItemId);

        $storeItem = $this->storeItemRepo->find($storeItemId);

        $inventoryItem->sold_by_user_id = \Auth::user()->userId();
        $inventoryItem->save();

        $saleItem = $this->repo->create(array_merge($attributes, [ 'inventory_id' => $inventoryItem->id, 'name' => $storeItem->item->name ]));

        if (!is_null($saleItem->work_order_id))
        {
            $storeItem = $inventoryItem->storeItem;

            if ($storeItem->stock < 0)
            {
                $workOrder = $saleItem->workOrder;

                $workOrderCache = $workOrder->workOrderCache;
                $workOrderCache->needs_to_order_parts ++;
                $workOrderCache->save();

                $this->workOrderService->updateStatus($workOrder, $workOrderCache);
            }
        }

        $taxIds = $this->storeTaxRepo->getTaxIds(\Auth::user()->storeId());

        // create sale item tax for each store tax
        foreach ($taxIds as $taxId)
        {
            $this->saleItemTaxRepo->create([
                'sale_item_id' => $saleItem->id,
                'store_tax_id' => $taxId->id
            ]);
        }

        //Create a WorkOrder Note if this was on a Work Order
        $workOrder = $saleItem->workOrder;

        if ($workOrder)
        {
            $this->workOrderNoteService->insertWorkOrderNote($workOrder->id, [
                    'workorder_status_id' => $workOrder->workorder_status_id,
                    'notes' => "added {$saleItem->inventory->storeItem->item->name}",
                    'user_id' => \Auth::user()->userId(),
                    'public' => false,
                    'auto' => true
                ]);
        }

        \DB::commit();

        return $saleItem;
    }

    public function destroy($id)
    {
        $saleItem = $this->find($id);

        if (!is_null($saleItem->work_order_id))
        {
            $inventoryItem = $this->inventoryService->find($saleItem->inventory_id);
            $storeItem = $inventoryItem->storeItem;

            if ($storeItem->stock < 0)
            {
                $workOrder = $saleItem->workOrder;
                $workOrderCache = $workOrder->workOrderCache;

                switch ($inventoryItem->status)
                {
                    case Inventory::STATUS_RECEIVED:
                        break;
                    case Inventory::STATUS_PURCHASED:
                        $workOrderCache->awaiting_parts --;
                        $workOrderCache->save();

                        $this->workOrderService->updateStatus($workOrder, $workOrderCache);
                        break;
                    case Inventory::STATUS_BACKORDERED:
                        $workOrderCache->needs_to_order_parts --;
                        $workOrderCache->save();

                        $this->workOrderService->updateStatus($workOrder, $workOrderCache);
                        break;
                }
            }
        }

        $inventoryCallback = $this->inventoryService->unreserveInventoryItemForSale($saleItem->inventory_id);

        //Create a WorkOrder Note if this was on a Work Order
        $workOrder = $saleItem->workOrder;

        if ($workOrder)
        {
            $this->workOrderNoteService->insertWorkOrderNote($workOrder->id, [
                    'workorder_status_id' => $workOrder->workorder_status_id,
                    'notes' => "deleted {$saleItem->inventory->storeItem->item->name}",
                    'user_id' => \Auth::user()->userId(),
                    'public' => false,
                    'auto' => true
                ]);
        }

        $this->repo->delete($id);

        if (is_callable($inventoryCallback)) {
            $inventoryCallback();
        }
    }

    public function update($id, $attributes)
    {
        $previousValue = $this->find($id);

        $result = parent::update($id, $attributes);

        $newValue = $this->find($id);

        //Create a WorkOrder Note if this was on a Work Order
        $workOrder = $newValue->workOrder;

        if (($previousValue->price != $newValue->price) && $workOrder)
        {
            $this->workOrderNoteService->insertWorkOrderNote($workOrder->id, [
                    'workorder_status_id' => $workOrder->workorder_status_id,
                    'notes' => "updated {$newValue->inventory->storeItem->item->name} price from {$previousValue->price} to {$newValue->price}",
                    'user_id' => \Auth::user()->userId(),
                    'public' => false,
                    'auto' => true
                ]);
        }

        if ($attributes['inventory_id'] && $attributes['inventory_id'] != $previousValue['inventory_id']) {
            $this->inventoryService->unreserveInventoryItemForSale($previousValue['inventory_id']);
            $inventoryItem = $this->inventoryService->find($attributes['inventory_id']);
            $this->inventoryService->reserveInventoryItem($inventoryItem);

            $inventoryItem->sold_by_user_id = \Auth::user()->userId();
            $inventoryItem->save();
        }

        return $result;
    }

    public function getAllWhereSaleCompleted($startDate, $endDate)
    {
        return $this->repo->getAllWhereSaleCompleted($startDate, $endDate);
    }

    public function getAllWhereSaleNotCompleted($startDate, $endDate)
    {
        return $this->repo->getAllWhereSaleNotCompleted($startDate, $endDate);
    }

    public function getAllForStoreBetweenDates($select, $storeId, $startDate, $endDate)
    {
        return $this->repo->getAllForStoreBetweenDates($select, $storeId, $startDate, $endDate);
    }
}