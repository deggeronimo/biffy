<?php namespace Biffy\Http\Controllers;

use Auth;
use Biffy\Entities\WorkOrderStatus\WorkOrderStatus;
use Biffy\Http\Requests\Pos\CreatePosWarrantyRequest;
use Biffy\Http\Requests\Pos\CreatePosWorkOrderRequest;
use Biffy\Services\Entities\Device\DeviceService;
use Biffy\Services\Entities\Sale\SaleService;
use Biffy\Services\Entities\SaleItem\SaleItemService;
use Biffy\Services\Entities\StoreItem\StoreItemService;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;
use DB;

class PosController extends ApiController
{
    protected $deviceService;
    protected $saleService;
    protected $saleItemService;
    protected $storeItemService;
    protected $workOrderService;

    public function __construct(DeviceService $deviceService, SaleService $saleService, SaleItemService $saleItemService,
                                StoreItemService $storeItemService, WorkOrderService $workOrderService)
    {
        $this->deviceService = $deviceService;
        $this->saleService = $saleService;
        $this->saleItemService = $saleItemService;
        $this->storeItemService = $storeItemService;
        $this->workOrderService = $workOrderService;
    }

    public function postWarranty(CreatePosWarrantyRequest $request)
    {
        DB::beginTransaction();

        $oldWorkOrderId = $request->input('warranty_workorder_id');
        $oldWorkOrder = $this->workOrderService->find($oldWorkOrderId);

        $oldSale = $oldWorkOrder->sale;

        $newSale = $this->saleService->create([
            'customer_id' => $oldSale->customer_id,
            'store_id' => Auth::user()->storeId(),
            'user_id' => Auth::user()->userId(true)
        ]);

        $newWorkOrder = $this->workOrderService->create([
            'notes' => $request->input('notes'),
            'next_update' => $request->input('next_update'),
            'device_id' => $oldWorkOrder->device_id,
            'sale_id' => $newSale->id,
            'quickdiag' => $request->input('quickdiag'),
            'itemswithdevice' => $request->input('itemswithdevice'),
            'rating' => $request->input('rating'),
            'warranty_workorder_id' => $oldWorkOrderId,
            'workorder_status_id' => WorkOrderStatus::AWAITING_REPAIR
        ]);

        $saleItemList = $oldWorkOrder->saleItems;

        foreach ($saleItemList as $saleItem)
        {
            $this->saleItemService->create([
                'work_order_id' => $newWorkOrder->id,
                'store_item_id' => $saleItem->inventory->store_item_id,
                'price' => 0.00,
                'labor_cost' => 0.00,
                'discount' => 0
            ]);
        }

        DB::commit();

        return $this->data($newWorkOrder->toArray())->respond();
    }

    public function postWorkorder(CreatePosWorkOrderRequest $request)
    {
        DB::beginTransaction();

        //1) Create a New Device if one does not already exist
        $deviceId = $request->input('device_id');

        if (is_null($deviceId))
        {
            $device = $this->deviceService->create(
                $request->inputs([
                    'customer_id', 'device_name', 'device_passcode', 'device_serial', 'device_serial_type', 'device_type_id'
                ])
            );

            $deviceId = $device->id;
        }

        //2) Create a New Sale
        $saleId = $request->input('sale_id');

        if (is_null($saleId))
        {
            $sale = $this->saleService->create(
                array_merge(
                    $request->inputs([
                        'customer_id'
                    ]),
                    [
                        'store_id' => Auth::user()->storeId(),
                        'user_id' => Auth::user()->userId()
                    ]
                )
            );

            $saleId = $sale->id;
        }

        //3) Create a New WorkOrder
        $workOrder = $this->workOrderService->create(
            array_merge(
                $request->inputs([
                    'quickdiag', 'itemswithdevice', 'notes', 'rating', 'next_update', 'workorder_status_id', 'warranty_workorder_id'
                ]),
                [
                    'device_id' => $deviceId,
                    'sale_id' => $saleId
                ]
            )
        );

        //4) Add Items to the WorkOrder
        $itemIdList = json_decode($request->input('item_id_list'), true);

        foreach ($itemIdList as $itemId)
        {
            $storeItem = $this->storeItemService->getOne(Auth::user()->storeId(), $itemId)[0];

            $this->saleItemService->create([
                'work_order_id' => $workOrder->id,
                'store_item_id' => $storeItem->id,
                'price' => $storeItem->unit_price,
                'labor_cost' => $storeItem->labor_cost,
                'discount' => 0
            ]);
        }

        DB::commit();

        return $this->data($workOrder->toArray())->respond();
    }
}