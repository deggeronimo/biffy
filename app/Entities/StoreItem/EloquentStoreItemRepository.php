<?php namespace Biffy\Entities\StoreItem;

use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Entities\Item\Item;
use Biffy\Entities\Store\Store;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class EloquentStoreItemRepository
 * @package Biffy\Entities\StoreItem
 */
class EloquentStoreItemRepository extends EloquentAbstractRepository implements StoreItemRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'item.number' => [],
        'item.name' => [],
        'last_cost' => [],
        'stock' => [],
        'reserved' => [],
        'on_order' => []
    ];

    /**
     * @var array
     */
    protected $filters = [
        'store_id' => [ 'store_id = ?', ':value' ],
        'search' => [ '(items.name LIKE ? OR item_id = ?)', '%:value%', ':value' ],
        'device_type_id' => [ 'items.device_type_id = ?', ':value' ],
        'not_device_type_id' => [ 'items.device_type_id != ?', ':value' ],
        'negative_stock' => [ 'stock < items.required' ],
        'positive_stock' => [ 'stock >= items.required' ]
    ];

    /**
     * @var Item
     */
    private $itemModel;

    /**
     * @var Store
     */
    private $storeModel;

    /**
     * @param StoreItem $model
     * @param Item $itemModel
     * @param Store $storeModel
     */
    public function __construct(StoreItem $model, Item $itemModel, Store $storeModel)
    {
        $this->model = $model;
        $this->itemModel = $itemModel;
        $this->storeModel = $storeModel;

        $this->with(['item', 'item.deviceType']);
    }

    public function doesItemIdExist($storeId, $itemId)
    {
        $result = $this->make()
            ->where('store_items.store_id', '=', $storeId)
            ->where('store_items.item_id', '=', $itemId)
            ->count();

        return $result > 0;
    }

    public function getOne($storeId, $itemId)
    {
        $query = $this->make()
            ->join('items', 'store_items.item_id', '=', 'items.id')
            ->where('store_id', '=', $storeId)
            ->where('item_id', '=', $itemId)
            ->select([ 'items.name', 'store_items.*' ]);

        return $query->get();
    }

    /**
     * @param $storeId
     * @param null $filter
     * @return mixed
     */
    public function getAllStoreItems($storeId, $filter = null)
    {
        $query = $this->make()
            ->join('items', 'store_items.item_id', '=', 'items.id')
            ->join('device_types', 'items.device_type_id', '=', 'device_types.id')
            ->where('store_items.store_id', '=', $storeId)
            ->orderBy('items.name')
            ->select(['device_types.*', 'items.*', 'store_items.*']);

        if (!is_null($filter) && isset($filter['name']))
        {
            $query = $query->where('items.name', 'LIKE', '%' . $filter['name'] . '%');
            $query->take(25);
        }

        if (!is_null($filter) && isset($filter['stock']))
        {
            $query = $query->where('stock', '<', 0);
        }

        $result = $query->get();

        return $result;
    }

    /**
     * @param $storeId
     * @param $filter
     * @param $sorting
     * @param $count
     * @param $page
     * @return mixed
     */
    public function getStoreItems($storeId, $filter, $sorting, $count, $page)
    {
        $total = $this->make()
            ->with(['item', 'item.deviceType'])
            ->join('items', 'store_items.item_id', '=', 'items.id')
            ->where('store_items.store_id', '=', $storeId);

        if (!is_null($filter) && isset($filter['name']))
        {
            $total = $total->where('items.name', 'LIKE', '%' . $filter['name'] . '%');
        }

        $total = $total->count();

        $result = $this->make()
            ->join('items', 'store_items.item_id', '=', 'items.id')
            ->select(['items.*', 'store_items.*'])
            ->where('store_items.store_id', '=', $storeId)
            ->orderBy('items.item_type_id', 'asc');

        if (!is_null($sorting))
        {
            foreach ($sorting as $sortBy => $sortOrder)
            {
                $result->orderBy($sortBy, $sortOrder);
            }
        }

        if (!is_null($filter) && isset($filter['search']))
        {
            $result = $result->where('items.name', 'LIKE', '%' . $filter['search'] . '%');
        }

        $result = $result->paginate($count)->toArray()['data'];

        return new LengthAwarePaginator($result, $total, $count, $page);
    }

    protected function preGet()
    {
        $this->query->join('items', 'store_items.item_id', '=', 'items.id');
    }

    public function selectStoreItemTableFirst($filter, $sorting, $defaultCount)
    {
        $this->filterBy($filter)->sortBy($sorting)->paginate($defaultCount);
        return $this->get(['store_items.*']);
    }
}

