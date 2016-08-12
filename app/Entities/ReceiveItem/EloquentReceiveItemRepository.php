<?php namespace Biffy\Entities\ReceiveItem;

use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Entities\Inventory\Inventory;
use Biffy\Entities\PurchaseItem\PurchaseItem;
use Biffy\Entities\PurchaseOrder\PurchaseOrder;
use Biffy\Entities\StoreItem\StoreItem;

/**
 * Class EloquentReceiveItemRepository
 * @package Biffy\Entities\ReceiveItem
 */
class EloquentReceiveItemRepository extends EloquentAbstractRepository implements ReceiveItemRepositoryInterface
{
    /**
     * @var Inventory
     */
    private $inventoryModel;

    /**
     * @var PurchaseItem
     */
    private $purchaseItemModel;

    /**
     * @var PurchaseOrder
     */
    private $purchaseOrderModel;

    /**
     * @var
     */
    private $storeItemModel;

    /**
     * @param ReceiveItem $model
     * @param Inventory $inventoryModel
     * @param PurchaseItem $purchaseItemModel
     * @param PurchaseOrder $purchaseOrderModel
     * @param StoreItem $storeItemModel
     */
    public function __construct(ReceiveItem $model, Inventory $inventoryModel,
                                PurchaseItem $purchaseItemModel, PurchaseOrder $purchaseOrderModel,
                                StoreItem $storeItemModel)
    {
        $this->model = $model;

        $this->inventoryModel = $inventoryModel;
        $this->purchaseItemModel = $purchaseItemModel;
        $this->purchaseOrderModel = $purchaseOrderModel;
        $this->storeItemModel = $storeItemModel;
    }

    /**
     * @param $purchaseItemId
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getReceiveItemsForPurchaseItem($purchaseItemId)
    {
        return $this->purchaseItemModel->find($purchaseItemId)->receiveItems();
    }

    /**
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getReceiveItemsForPurchaseOrder($purchaseOrderId)
    {
        return $this->purchaseOrderModel->find($purchaseOrderId)->receiveItems();
    }
}

