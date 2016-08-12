<?php namespace Biffy\Services\Entities\PurchaseItem;

use Biffy\Entities\PurchaseItem\PurchaseItemRepositoryInterface;
use Biffy\Services\Entities\Service;

/**
 * Class PurchaseItemService
 * @package Biffy\Services\Entities\PurchaseItem
 */
class PurchaseItemService extends Service
{
    /**
     * @param PurchaseItemRepositoryInterface $repo
     */
    public function __construct(PurchaseItemRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getModelByIDwithPurchaseOrder($id)
    {
        return $this->repo->with('PurchaseOrder')->find($id);
    }

    /**
     * @param $filterBy
     * @return mixed
     */
    public function getItemsWithPurchaseOrder($filterBy)
    {
        return $this->repo->getItemsWithPurchaseOrder($filterBy)->toArray();
    }

    /**
     * @param int $id
     * @param array $attributes
     *
     * @return boolean
     */
    public function update($id, $attributes)
    {
//        $purchaseItem = $this->repo->find($id);
//        $storeItem = $this->storeItemRepo->find($purchaseItem->store_item_id);

//        $storeItem->on_order = $storeItem->on_order - $purchaseItem->quantity + $attributes['quantity'];
//        $storeItem->save();

        return $this->repo->update($id, $attributes);
    }
}
