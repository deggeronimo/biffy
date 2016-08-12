<?php namespace Biffy\Entities\Inventory;

use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Entities\Store\Store;
use Biffy\Entities\StoreItem\StoreItem;
use Illuminate\Support\Collection;

/**
 * Class EloquentInventoryRepository
 * @package Biffy\Entities\Inventory
 */
class EloquentInventoryRepository extends EloquentAbstractRepository implements InventoryRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'id' => [],
        'cost' => [],
        'created_at' => []
    ];

    /**
     * @var array
     */
    protected $filters = [
        'store_item_id' => [ 'store_item_id = ?', ':value' ],
        'in_stock' => [ 'status = 1' ],
        'on_order' => [ 'status = 2' ],
        'back_order' => [ 'status = 3' ]
    ];

    /**
     * @var Store
     */
    private $storeModel;

    /**
     * @var StoreItem
     */
    private $storeItemModel;

    /**
     * @param Inventory $model
     * @param Store $storeModel
     * @param StoreItem $storeItemModel
     */
    public function __construct(Inventory $model, Store $storeModel, StoreItem $storeItemModel)
    {
        $this->model = $model;
        $this->storeModel = $storeModel;
        $this->storeItemModel = $storeItemModel;

        $this->with([ 'saleItem', 'saleItem.taxes', 'saleItem.taxes.storeTax', 'soldByUser', 'vendor' ]);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getInventoryForStore($storeId)
    {
        return $this->storeModel->find($storeId)->inventory()->with('storeItem')->get();
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getInventoryCountsForStore($storeId)
    {
        $inventoryList = $this->storeModel->find($storeId)->inventory()->with('storeItem')->get()->toArray();

        $counts = [];

        foreach ($inventoryList as $key => $value)
        {
            if (!isset($counts[$value['store_item_id']]))
            {
                $counts[$value['store_item_id']] = [
                    'item_id' => $value['store_item']['item_id'],
                    'quantity' => $value['store_item']['quantity']
                ];
            }
        }

        return new Collection([ 'counts' => $counts ]);
    }

    /**
     * @param int $storeItemId
     * @return mixed
     */
    public function getInventoryForItem($storeItemId)
    {
        return $this->model->with('storeItem')->where('store_item_id', '=', $storeItemId)->get();
    }

    /**
     * @param int $storeItemId
     * @return int
     */
    public function getInventoryCountForItem($storeItemId)
    {
        return new Collection([
            'count' => $this->model->with('storeItem')->where('store_item_id', '=', $storeItemId)->count()
        ]);
    }

    /**
     * @deprecated
     * @param int $id
     * @return mixed
     */
    public function getInventoryById($id)
    {
        return $this->model->find($id);
    }
}