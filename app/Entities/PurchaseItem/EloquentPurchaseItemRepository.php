<?php namespace Biffy\Entities\PurchaseItem;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentPurchaseItemRepository
 * @package Biffy\Entities\PurchaseItem
 */
class EloquentPurchaseItemRepository extends EloquentAbstractRepository implements PurchaseItemRepositoryInterface
{
    /**
     * @param PurchaseItem $model
     */
    public function __construct(PurchaseItem $model)
    {
        $this->model = $model;

        $this->with([
            'backOrderItems'
        ]);
    }

    /**
     * @param $purchaseOrderId
     * @param $storeItemId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator|static[]
     */
    public function getPurchaseItemBy($purchaseOrderId, $storeItemId)
    {
        $query = $this->make();

        $query->where('purchase_order_id', '=', $purchaseOrderId);
        $query->where('store_item_id', '=', $storeItemId);

        return $query->get();
    }

    /**
     * @param $filterBy
     * @return mixed
     */
    public function getItemsWithPurchaseOrder($filterBy)
    {
        $query = $this->make();
        $query->with(['storeItem', 'storeItem.item', 'receiveItems']);

        if (!is_null($filterBy))
        {
            foreach ($filterBy as $key => $value)
            {
                if ($key == 'purchase_order_id')
                {
                    $query->where($key, '=', $value);
                }
            }
        }

        return $query->get();
    }
}
