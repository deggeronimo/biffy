<?php namespace Biffy\Http\Controllers\Reports;

use Biffy\Entities\SaleItem\SaleItemRepositoryInterface;

class InventoryReporter extends AbstractReporter
{
    protected $saleItemRepo;

    public function __construct(SaleItemRepositoryInterface $saleItemRepo)
    {
        $this->saleItemRepo = $saleItemRepo;
    }

    public function generateDetailedReport($storeId, $startTime, $endTime)
    {
        $numberOfDays = (strtotime($endTime) - strtotime($startTime)) / 86400;

        $resultList = [];

        $saleItemList = $this->saleItemRepo->with([])->getAllForStoreBetweenDates(
            [
                'store_items.item_id',
                'inventory.store_item_id',
                'store_items.stock',
                'items.name',
                'items.distro_price',
                'sale_items.*'
            ],
            $storeId, $startTime, $endTime
        );

        $saleItemListIterator = $saleItemList->getIterator();

        while ($saleItemListIterator->valid())
        {
            $saleItem = $saleItemListIterator->current();

            $storeItemId = $saleItem->store_item_id;

            $required = $saleItem->inventory->storeItem->item->required;

            $newResultEntry = [
                'store_item_id' => $storeItemId,
                'item' => [
                    'id' => $saleItem->item_id,
                    'name' => $saleItem->name,
                    'distro_price' => $saleItem->distro_price
                ],
                'stock' => $saleItem->stock,
                'history' => [],
                'total_sold' => 0,
                'recommend' => $saleItem->stock < $required ? $required - $saleItem->stock : 0
            ];

            $result = & $this->getRecordByField($resultList, 'store_item_id', $storeItemId, $newResultEntry);

            $result['total_sold'] ++;

            $soldPerDay = $result['total_sold'] / $numberOfDays;

            $result['recommend'] = $required + round(14 * $soldPerDay + 0.5) - $saleItem->stock;
            $result['recommend'] = $result['recommend'] < 0 ? 0 : $result['recommend'];

            $result['shortage'] = round(($result['stock'] + $result['recommend']) / $soldPerDay, 3);

            $result['history'][] = [
                'id' => $saleItem->id,
                'sale_id' => $saleItem->sale_id,
                'work_order_id' => $saleItem->work_order_id,
                'price' => $saleItem->price,
                'labor_cost' => $saleItem->labor_cost,
                'discount' => $saleItem->discount,
                'on_receipt' => $saleItem->on_receipt,
                'tax_exempt' => $saleItem->tax_exempt,
                'created_at' => strtotime($saleItem->created_at->toDateTimeString())
            ];

            $saleItemListIterator->next();
        }

        return $resultList;
    }
}