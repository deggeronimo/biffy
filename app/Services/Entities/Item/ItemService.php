<?php namespace Biffy\Services\Entities\Item;

use Biffy\Entities\Item\ItemRepositoryInterface;
use Biffy\Entities\StoreItem\StoreItemRepositoryInterface;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\Store\StoreService;
use Biffy\Services\Entities\StoreItem\StoreItemService;
use Illuminate\Support\Facades\Auth;

/**
 * Class ItemService
 * @package Biffy\Services\Entities\Item
 */
class ItemService extends Service
{
    protected $storeItemRepo;

    /**
     * @param ItemRepositoryInterface $repo
     * @param StoreItemRepositoryInterface $storeItemRepo
     */
    public function __construct(ItemRepositoryInterface $repo, StoreItemRepositoryInterface $storeItemRepo)
    {
        $this->repo = $repo;
        $this->storeItemRepo = $storeItemRepo;
    }

    public function getItemWithStoreItem($storeId, $id)
    {
        $item = $this->find($id);
        $storeItem = $this->storeItemRepo->getOne($storeId, $id);

        $item->store_item = $storeItem->toArray()[0];

        return $item;
    }

    public function getUnattachedItems($search)
    {
        $itemsNotAttachedToStore = [];

        $this->repo->addFilter('search', $search);
        $allNonGlobalItems = $this->repo->findAllBy('global', '0');

        foreach($allNonGlobalItems as $item)
        {
            if (!$this->storeItemRepo->doesItemIdExist(Auth::user()->storeId(), $item->id))
            {
                $itemsNotAttachedToStore[] = $item->toArray();
            }
        }

        return $itemsNotAttachedToStore;
    }

    public function create($attributes)
    {
        $item = parent::create($attributes);
        $this->handleNew($item);
        return $item;
    }

    public function handleNew($item)
    {
        if ($item->global == '1') {
            /** @var StoreService $storeService */
            $storeService = app('Biffy\Services\Entities\Store\StoreService');
            $storeIds = $storeService->getAllIds();
        } else {
            $storeIds = [ \Auth::user()->storeId() ];
        }

        $data = array_map(function ($val) use ($item) {
                return [
                    'item_id' => $item->id,
                    'store_id' => $val,
                    'stock' => 0,
                    'on_order' => 0,
                    'last_cost' => '0.0',
                    'unit_price' => $item->unit_price ? : '0.0',
                    'labor_cost' => $item->labor_cost ? : '0.0'
                ];
            }, $storeIds);

        /** @var StoreItemService $storeItemService */
        $storeItemService = app('Biffy\Services\Entities\StoreItem\StoreItemService');
        $storeItemService->handleNew($data);
    }
}