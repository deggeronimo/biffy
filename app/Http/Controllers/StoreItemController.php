<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\Inventory\Inventory;
use Biffy\Services\Entities\Inventory\InventoryService;
use Biffy\Services\Entities\Item\ItemService;
use Biffy\Services\Entities\StoreItem\StoreItemService;
use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreItemController
 * @package Biffy\Http\Controllers
 */
class StoreItemController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var StoreItemService
     */
    protected $service;

    /**
     * @param StoreItemService $service
     * @param InventoryService $inventoryService
     * @param ItemService $itemService
     */
    function __construct(StoreItemService $service, InventoryService $inventoryService, ItemService $itemService)
    {
        $this->service = $service;

        $this->inventoryService = $inventoryService;
        $this->itemService = $itemService;
    }

    public function index()
    {
        $filter = $this->input('filter');
        $sorting = $this->input('sorting');

        if (!is_null($this->input('all')))
        {
            $deviceId = $this->input('device_type_id') ? : null;

            $result = $this->service->getList($filter, $sorting, $deviceId, \Auth::user()->storeId());

            if (!is_null($this->getMorph('list')))
            {
                $result = $this->morph([ 'results' => $result->toArray() ], $this->morphTo);
                // todo remove need for array_key_exists check (handle above statement better)
                return $this->data(array_key_exists('results', $result) ? $result['results'] : [])->respond();
            }
            else
            {
                return $this->data($result->toArray())->respond();
            }
        }
        else
        {
            $perPage = \StoreConfig::get('results-per-page');
            $perPage = (is_null($perPage)?10:$perPage);

            $count = $this->input('count', $perPage);
            $page = $this->input('page', 1);

            $result = $this->service->getStoreItems(\Auth::user()->storeId(), $filter, $sorting, $count, $page);

            return $this->data($result->toArray()['data'])->paginator($result)->respond();
        }
    }

    public function show($id)
    {
        $storeId = \Auth::user()->storeId();

        $result = $this->service->find($id)->toArray();

        if ($result['store_id'] != $storeId)
        {
            return $this->statusNotFound()->respond();
        }

        return $this->data($result)->respond();
    }

    public function destroy($id)
    {
        $storeId = \Auth::user()->storeId();

        $result = $this->service->find($id)->toArray();

        if ($result['store_id'] != $storeId)
        {
            return $this->statusNotfound()->respond();
        }

        $this->service->destroy($id);

        return $this->statusDeleted()->respond();
    }

    /**
     * @param $id
     * @param array $result
     */
    protected function afterUpdate($id, array $result)
    {
        try
        {
            \DB::commit();
        }
        catch(Exception $e)
        {
            \DB::rollback();
        }
    }

    protected function beforeUpdate($id, array $input)
    {
        $storeItem = $this->service->find($id);

        \DB::beginTransaction();

        if ($input['stock'] != $storeItem->stock)
        {
            //TODO: Adjust inventory table
            $delta = $input['stock'] - $storeItem->stock;

            if ($delta > 0)
            {
                for ($i = 0; $i < $delta; $i ++)
                {
                    $this->inventoryService->create([
                        'store_item_id' => $id,
                        'cost' => $storeItem->last_cost,
                        'status' => Inventory::STATUS_RECEIVED
                    ]);
                }
            }
            else if ($delta < 0)
            {
                $delta = -$delta;

                for ($i = 0; $i < $delta; $i ++)
                {
                    $inventoryItem = $this->inventoryService->reserveInventoryItemForDeletion($id);

                    if (!is_null($inventoryItem))
                    {
                        $inventoryItem->delete();
                    }
                    else
                    {
                        break;
                    }
                }

                //If there weren't enough items to delete, adjust the stock to reflect
                //Should be 0?
                $input['stock'] = $input['stock'] + ($delta - $i);
            }
        }

        return $input;
    }

    /**
     * @param Model $result
     */
    protected function afterStore(Model $result)
    {
        $item = $this->itemService->find($result->item_id);

        $result->unit_price = $item->unit_price;
        $result->labor_cost = $item->labor_cost;

        $result->save();
    }
}