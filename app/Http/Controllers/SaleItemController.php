<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\SaleItem\SaleItem;
use Biffy\Services\Entities\Inventory\InventoryService;
use Biffy\Services\Entities\SaleItem\SaleItemService;
use Biffy\Services\Entities\StoreItem\StoreItemService;
use Biffy\Services\Entities\WorkOrderNote\WorkOrderNoteService;

/**
 * Class SaleItemController
 * @package Biffy\Http\Controllers
 */
class SaleItemController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var SaleItemService
     */
    protected $service;

    protected $inventoryService;
    protected $storeItemService;
    protected $workOrderNoteService;

    /**
     * @param SaleItemService $service
     * @param InventoryService $inventoryService
     * @param StoreItemService $storeItemService
     * @param WorkOrderNoteService $workOrderNoteService
     */
    public function __construct(SaleItemService $service, InventoryService $inventoryService, StoreItemService $storeItemService, WorkOrderNoteService $workOrderNoteService)
    {
        $this->service = $service;

        $this->inventoryService = $inventoryService;
        $this->storeItemService = $storeItemService;
        $this->workOrderNoteService = $workOrderNoteService;
    }

    public function index()
    {
        return $this->data([])->statusOk()->respond();
    }

    protected function beforeStore(array $input)
    {
        \DB::beginTransaction();

        $storeItem = $this->storeItemService->find($input['store_item_id']);

        if (isset($input['company_id']))
        {
            $companyStoreItem = $storeItem->companyStoreItems()->where('company_id', $input['company_id'])->first();

            if (!is_null($companyStoreItem))
            {
                $storeItem = $companyStoreItem;
            }
        }

        $input['price'] = $storeItem->unit_price;

        if (\StoreConfig::get('labor-split-cost')) {
            $input['price'] -= $storeItem->labor_cost;
            $input['labor_cost'] = $storeItem->labor_cost;
        }
        return $input;
    }

    /**
     * @param SaleItem $result
     * @return SaleItem
     */
    protected function afterStore(SaleItem $result)
    {
        \DB::commit();

        //Allow the repository to get more information about the SaleItem so that another request
        //  does not need to be made to get it with all of the information needed
        $result = $this->service->find($result->id);

        return $result;
    }

    protected function storeError()
    {
        \DB::rollback();
    }

    protected function beforeDestroy($id)
    {
        \DB::beginTransaction();
    }

    protected function afterDestroy($id)
    {
        \DB::commit();
    }

    protected function destroyError()
    {
        \DB::rollback();
    }
}