<?php namespace Biffy\Services\Entities\Inventory;

use Biffy\Commands\Inventory\AddInventoryCommand;
use Biffy\Entities\Inventory\Inventory;
use Biffy\Entities\Inventory\InventoryRepositoryInterface;
use Biffy\Facades\Command;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\StoreItem\StoreItemService;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;

/**
 * Class InventoryService
 * @package Biffy\Services\Entities\Inventory
 */
class InventoryService extends Service
{
    protected $storeItemService;
    /**
     * @var WorkOrderService
     */
    protected $workOrderService;

    /**
     * @param InventoryRepositoryInterface $repo
     * @param StoreItemService $storeItemService
     * @param WorkOrderService $workOrderService
     */
    public function __construct(InventoryRepositoryInterface $repo, StoreItemService $storeItemService, WorkOrderService $workOrderService)
    {
        $this->repo = $repo;
        $this->storeItemService = $storeItemService;

        $this->workOrderService = $workOrderService;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    public function create($attributes)
    {
//        $result = $this->repo->create($attributes)->toArray();

        $command = new AddInventoryCommand([
            'increment' => 1,
            'attributes' => $attributes
        ]);

        Command::execute($command);

        return $command->result;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function show($id)
    {
        return $this->repo->show($id)->toArray();
    }

    /**
     * @param int $id
     * @param array $attributes
     *
     * @return array
     */
    public function update($id, $attributes)
    {
        return $this->repo->update($id, $attributes)->toArray();
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function destroy($id)
    {
        $inventoryItem = $this->find($id);

        $count = $this->repo->delete($id);

        if ( $count == 1 )
        {
            $storeItem = $this->storeItemService->find($inventoryItem->store_item_id);

            switch ($inventoryItem->status)
            {
                case Inventory::STATUS_BACKORDERED:
                    $storeItem->reserved --;
                    $storeItem->stock ++;
                    break;
                case Inventory::STATUS_PURCHASED:
                    $storeItem->on_order --;
                    break;
                case Inventory::STATUS_RECEIVED:
                    $storeItem->stock --;
                    break;
            }

            $storeItem->save();
        }

        return [];
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getInventoryForStore($storeId)
    {
        return $this->repo->getInventoryForStore($storeId)->toArray();
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getInventoryCountsForStore($storeId)
    {
        return $this->repo->getInventoryCountsForStore($storeId)->toArray();
    }

    /**
     * @param int $itemId
     * @return array
     */
    public function getInventoryForItem($itemId)
    {
        return $this->repo->getInventoryCountForItem($itemId)->toArray();
    }

    /**
     * @param int $itemId
     * @return array
     */
    public function getInventoryCountForItem($itemId)
    {
        return $this->repo->getInventoryCountForItem($itemId)->toArray();
    }

    public function reserveInventoryItemForDeletion($storeItemId)
    {
        $statusPriority = [
            [
                'sold_by_user_id' => null,
                'store_item_id' => $storeItemId,
                'status' => Inventory::STATUS_RECEIVED
            ]
        ];

        $inventoryItem = $this->reserveItemWithPriority($statusPriority);

        return $inventoryItem;
    }

    /**
     * @param $storeItemId
     * @return mixed|null
     */
    public function reserveInventoryItemForPurchase($storeItemId)
    {
        $statusPriority = [
            [
                'store_item_id' => $storeItemId,
                'status' => Inventory::STATUS_BACKORDERED
            ]
        ];

        $inventoryItem = $this->reserveItemWithPriority($statusPriority);

        if (is_null($inventoryItem))
        {
            $inventoryItem = $this->repo->create([
                'store_item_id' => $storeItemId,
                'status' => Inventory::STATUS_PURCHASED
            ]);
        }

        return $inventoryItem;
    }

    /**
     * @param $storeItemId
     * @return mixed|null
     */
    public function reserveInventoryItemForReceive($storeItemId)
    {
        $statusPriority = [
            [
                'store_item_id' => $storeItemId,
                'status' => Inventory::STATUS_PURCHASED
            ]
        ];

        $inventoryItem = $this->reserveItemWithPriority($statusPriority);

        return $inventoryItem;
    }

    /**
     * @param $storeItemId
     * @return mixed|null
     */
    public function reserveInventoryItemForSale($storeItemId)
    {
        $statusPriority = [
            [
                'sold_by_user_id' => null,
                'store_item_id' => $storeItemId,
                'status' => Inventory::STATUS_RECEIVED
            ],
            [
                'store_item_id' => $storeItemId,
                'status' => Inventory::STATUS_PURCHASED
            ]
        ];

        $inventoryItem = $this->reserveItemWithPriority($statusPriority);

        if (is_null($inventoryItem))
        {
            $inventoryItem = $this->repo->create([
                'store_item_id' => $storeItemId,
                'status' => Inventory::STATUS_BACKORDERED
            ]);
        }

        $this->reserveInventoryItem($inventoryItem);

        return $inventoryItem;
    }

    public function reserveInventoryItem($inventoryItem)
    {
        $storeItem = $this->storeItemService->find($inventoryItem['store_item_id']);
        $storeItem->stock --;
        $storeItem->reserved ++;
        $storeItem->save();
    }

    /**
     * @param $statusPriority
     * @return mixed|null
     */
    private function reserveItemWithPriority($statusPriority)
    {
        $inventoryItem = null;

        foreach ($statusPriority as $inventoryAttributes)
        {
            $inventoryItem = $this->repo->firstByAttributes($inventoryAttributes);

            if (!is_null($inventoryItem))
            {
                break;
            }
        }

        return $inventoryItem;
    }

    /**
     * @param $inventoryId
     * @return callable|null
     */
    public function unreserveInventoryItemForSale($inventoryId)
    {
        $return = null;
        $inventoryItem = $this->repo->find($inventoryId);
        $storeItem = $this->storeItemService->find($inventoryItem->store_item_id);

        $inventoryItem->sold_by_user_id = null;

        if ($inventoryItem->status == Inventory::STATUS_BACKORDERED)
        {
            $return = function () use ($inventoryId) {
                $this->repo->delete($inventoryId);
            };
            $inventoryItem = null;
        }
        else
        {
            $inventoryItem->save();
        }

        $storeItem->stock ++;
        $storeItem->reserved --;
        $storeItem->save();

        return $return;
    }

    public function unreserveInventoryItemForReceive($storeItemId)
    {
        $statusPriority = [
            [
                'sold_by_user_id' => null,
                'store_item_id' => $storeItemId,
                'status' => Inventory::STATUS_RECEIVED
            ]
        ];

        $inventoryItem = $this->reserveItemWithPriority($statusPriority);

        if (is_null($inventoryItem))
        {
            return false;
        }

        $inventoryItem->received_by_user_id = null;
        $inventoryItem->status = Inventory::STATUS_PURCHASED;
        $inventoryItem->save();

        $storeItem = $this->storeItemService->find($inventoryItem->store_item_id);
        $storeItem->stock --;
        $storeItem->save();

        return true;
    }
}