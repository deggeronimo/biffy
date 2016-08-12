<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\Inventory\Inventory;
use Biffy\Entities\SalePaymentType\SalePaymentType;
use Biffy\Entities\WorkOrderStatus\WorkOrderStatus;
use Biffy\Services\Entities\CompanyStoreItem\CompanyStoreItemService;
use Biffy\Services\Entities\Customer\CustomerService;
use Biffy\Services\Entities\Inventory\InventoryService;
use Biffy\Services\Entities\Quote\QuoteService;
use Biffy\Services\Entities\RecommendedItem\RecommendedItemService;
use Biffy\Services\Entities\Sale\SaleService;
use Biffy\Services\Entities\SalePayment\SalePaymentService;
use Biffy\Services\Entities\StoreTax\StoreTaxService;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class SaleController
 * @package Biffy\Http\Controllers
 */
class SaleController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var SaleService $service
     */
    protected $service;

    protected $customerService;
    protected $inventoryService;
    protected $quoteService;
    protected $recommendedItemService;
    protected $salePaymentService;
    protected $storeTaxService;
    protected $workOrderService;

    private $oldQuote;

    /**
     * @param SaleService $service
     * @param CompanyStoreItemService $companyStoreItemService
     * @param CustomerService $customerService
     * @param InventoryService $inventoryService
     * @param QuoteService $quoteService
     * @param RecommendedItemService $recommendedItemService
     * @param SalePaymentService $salePaymentService
     * @param StoreTaxService $storeTaxService
     * @param WorkOrderService $workOrderService
     */
    public function __construct(SaleService $service, CompanyStoreItemService $companyStoreItemService, CustomerService $customerService,
                                InventoryService $inventoryService, QuoteService $quoteService,
                                RecommendedItemService $recommendedItemService, SalePaymentService $salePaymentService,
                                StoreTaxService $storeTaxService, WorkOrderService $workOrderService)
    {
        $this->service = $service;

        $this->companyStoreItemService = $companyStoreItemService;
        $this->customerService = $customerService;
        $this->inventoryService = $inventoryService;
        $this->quoteService = $quoteService;
        $this->recommendedItemService = $recommendedItemService;
        $this->salePaymentService = $salePaymentService;
        $this->storeTaxService = $storeTaxService;
        $this->workOrderService = $workOrderService;
    }

    private function calcSaleItems( & $saleItems, &$money, $companyDiscount)
    {
        $taxLabor = \StoreConfig::get('labor-tax') == 1;

        foreach ($saleItems as & $saleItem)
        {
            $money['items_in_cart'] ++;

            $saleItem['subtotal'] = round(($saleItem['price'] + $saleItem['labor_cost']) * (1 - ($saleItem['discount'] + $companyDiscount) / 100), 2);
            $saleItem['orig'] = [
                'price' => $saleItem['price'],
                'labor_cost' => $saleItem['labor_cost'],
                'discount' => $saleItem['discount']
            ];
            $saleItem['taxes_due'] = 0;

            if ($taxLabor) {
                $saleItem['taxable'] = $saleItem['subtotal'];
            } else {
                $saleItem['taxable'] = round($saleItem['price'] * (1 - ($saleItem['discount'] + $companyDiscount) / 100), 2);
            }

            if ($saleItem['tax_exempt'] == 0)
            {
                foreach ($saleItem['taxes'] as & $tax)
                {
                    $tax['amount'] = round($saleItem['taxable'] * $tax['store_tax']['percentage'], 2);
                    $saleItem['taxes_due'] += $tax['amount'];
                }
            }

            $money['subtotal'] += $saleItem['subtotal'];
            $money['taxes_due'] += $saleItem['taxes_due'];
        }
    }

    public function show($id)
    {
        $result = $this->service->find($id);

        $result = $result->toArray();

        $money = [
            'adjustments' => 0,
            'items_in_cart' => 0,
            'payments' => 0,
            'subtotal' => 0,
            'taxes_due' => 0,
            'trade_credit' => 0,
            'refunds' => 0,
            'view_tax_rate' => 0
        ];

        $storeTaxList = $this->storeTaxService->findAllBy('store_id', \Auth::user()->storeId());

        foreach ($storeTaxList as $storeTax)
        {
            if ($storeTax->active == '1')
            {
                $money['view_tax_rate'] += $storeTax->percentage;
            }
        }

        $company = $result['company'];
        $companyDiscount = is_null($company) ? 0 : $company['discount'];

        $this->calcSaleItems($result['sale_items'], $money, $companyDiscount);

        foreach ($result['work_orders'] as & $workOrder)
        {
            $workOrder['recommended_items'] = $this->recommendedItemService->allFromWorkOrder($workOrder['id']);
            $this->calcSaleItems($workOrder['sale_items'], $money, $companyDiscount);
        }

        foreach ($result['sale_payments'] as & $salePayment)
        {
            switch ($salePayment['sale_payment_type_id'])
            {
                case SalePaymentType::CASH:
                case SalePaymentType::CREDIT:
                case SalePaymentType::GIFT_CARD:
                    $money['payments'] += $salePayment['amount'];
                    break;
                case SalePaymentType::ADJUSTMENT_AMOUNT:
                case SalePaymentType::ADJUSTMENT_DISCOUNT:
                case SalePaymentType::ADJUSTMENT_COMP:
                    $money['adjustments'] += $salePayment['amount'];
                    break;
                case SalePaymentType::TRADE_CREDIT:
                    $money['trade_credit'] += $salePayment['amount'];
                    break;
                case SalePaymentType::CASH_REFUND:
                case SalePaymentType::CREDIT_REFUND:
                    $money['refunds'] += $salePayment['amount'];
                    break;
            }
        }

        $money['total'] = round($money['subtotal'] + $money['taxes_due'], 2);
        $money['total_due'] = round($money['total'] - $money['payments'] - $money['adjustments'] - $money['trade_credit'], 2);
        $money['value'] = round($money['total_due'], 2);

        $result['money'] = $money;

        $this->service->update($id, [
            'subtotal' => $money['subtotal'],
            'taxes' => $money['taxes_due'],
            'payments' => $money['payments'],
            'trade_credit' => $money['trade_credit'],
            'adjustments' => $money['adjustments'],
        ]);

        return $this->data($result)->statusOk()->respond();
    }

    protected function beforeStore(array $input)
    {
        $customer = $this->customerService->find($input['customer_id']);
        $input['company_id'] = $customer->company_id;

        return $input;
    }

    protected function beforeUpdate($id, array $input)
    {
        //TODO: If 'completed' == 1, update 'subtotal', 'taxesDue', 'payments', 'trade_credit', and 'adjustments'

        if (isset($input['quote_id']))
        {
            $this->oldQuote = $this->service->find($id)->quote;

            DB::beginTransaction();
        }

        return $input;
    }

    protected function afterUpdate($id, array $result)
    {
        if (isset($result['quote_id']))
        {
            try
            {
                $sale = $this->service->find($id);

                $quote = $sale->quote;
                $workOrderList = $sale->workOrders;

                if (!is_null($this->oldQuote))
                {
                    $this->oldQuote->delete();
                }

                foreach ($workOrderList as $workOrder)
                {
                    if (is_null($quote))
                    {
                        $workOrderCache = $workOrder->workOrderCache;

                        $this->workOrderService->updateStatus($workOrder, $workOrderCache);
                    }
                    else
                    {
                        $this->workOrderService->updateStatusTo($workOrder, WorkOrderStatus::QUOTED, 'This Work Order is a quote.');
                    }
                }

                DB::commit();
            }
            catch (Exception $e)
            {
                DB::rollback();
            }
        }
    }

    protected function beforeDestroy($id)
    {
        DB::beginTransaction();

        //1) Go through all the same items, set the inventory item sold_by_user_id to null.
        $sale = $this->service->find($id);
        $saleItemList = $sale->saleItems;

        foreach ($saleItemList as $saleItem)
        {
            $inventoryItem = $saleItem->inventory;

            if ($inventoryItem->status == Inventory::STATUS_BACKORDERED)
            {
                $this->inventoryService->destroy($inventoryItem->id);
            }
            else
            {
                $this->inventoryService->update($inventoryItem->id, [ 'sold_by_user_id' => null ]);
            }
        }

        //2) Repeat for each Work Order
        $workOrderList = $sale->workOrders;

        foreach ($workOrderList as $workOrder)
        {
            $saleItemList = $workOrder->saleItems;

            foreach ($saleItemList as $saleItem)
            {
                $inventoryItem = $saleItem->inventory;

                if ($inventoryItem->status == Inventory::STATUS_BACKORDERED)
                {
                    $this->inventoryService->destroy($inventoryItem->id);
                }
                else
                {
                    $this->inventoryService->update($inventoryItem->id, [ 'sold_by_user_id' => null ]);
                }
            }
        }
    }

    protected function afterDestroy($id)
    {
        DB::commit();
    }

    protected function destroyError()
    {
        DB::rollback();
    }
}
