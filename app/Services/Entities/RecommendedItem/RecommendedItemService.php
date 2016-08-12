<?php namespace Biffy\Services\Entities\RecommendedItem;

use Biffy\Services\Entities\Item\ItemService;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;

/**
 * Class RecommendedItemService
 * @package Biffy\Services\Entities\RecommendedItem
 */
class RecommendedItemService extends Service
{
    /**
     * @var ItemService
     */
    protected $itemService;
    /**
     * @var WorkOrderService
     */
    protected $workOrderService;

    /**
     * @param ItemService $itemService
     * @param WorkOrderService $workOrderService
     */
    public function __construct(ItemService $itemService, WorkOrderService $workOrderService)
    {
        $this->itemService = $itemService;
        $this->workOrderService = $workOrderService;
    }

    /**
     * @param $workOrderId
     * @return array|mixed
     */
    public function allFromWorkOrder($workOrderId)
    {
        $storeId = \Auth::user()->storeId();

        $workOrder = $this->workOrderService->find($workOrderId);

        $checkList = json_decode($workOrder->quickdiag);

        $result = [];

        foreach ($checkList as $checkItem)
        {
            if (!isset($checkItem->checked) || $checkItem->checked == '0')
            {
                $itemIdList = $checkItem->item_id;

                foreach ($itemIdList as $itemId)
                {
                    $found = false;

                    foreach ($workOrder->saleItems as $saleItem)
                    {
                        if ($saleItem->inventory->storeItem->item->id == $itemId)
                        {
                            $found = true;
                            break;
                        }
                    }

                    if (!$found && !in_array($itemId, $result))
                    {
                        $item = $this->itemService->getItemWithStoreItem($storeId, $itemId)->toArray();

                        $saleItem = [
                            'work_order_id' => $workOrderId,
                            'store_item_id' => $item['store_item']['id'],
                            'store_item' => $item['store_item'],
                            'price' => $item['store_item']['unit_price'],
                            'labor_cost' => $item['store_item']['labor_cost'],
                            'discount' => 0,
                            'on_receipt' => 1,
                            'tax_exempt' => 0
                        ];

                        $result[] = $saleItem;
                    }
                }
            }
        }

        return $result;
    }
}