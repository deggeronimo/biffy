<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\AccountExpenseCategory\AccountExpenseCategory;
use Biffy\Services\Entities\AccountExpense\AccountExpenseService;
use Biffy\Services\Entities\PurchaseOrder\PurchaseOrderService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class PurchaseOrderController
 * @package Biffy\Http\Controllers
 */
class PurchaseOrderController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var PurchaseOrderService
     */
    protected $service;

    /**
     * @param PurchaseOrderService $service
     * @param AccountExpenseService $accountExpenseService
     */
    public function __construct(PurchaseOrderService $service, AccountExpenseService $accountExpenseService)
    {
        $this->service = $service;

        $this->accountExpenseService = $accountExpenseService;
    }

    protected function beforeUpdate($id, array $input)
    {
        if ($input['finalized'] == 1)
        {
            $purchaseOrder = $this->service->find($id);

            if ($purchaseOrder->finalized == 1)
            {
                return null;
            }

            DB::beginTransaction();
        }

        return $input;
    }

    /**
     * @param $id
     * @param array $result
     * @throws Exception
     */
    protected function afterUpdate($id, array $result)
    {
        if ($result['finalized'] == 1)
        {
            try
            {
                $purchaseOrder = $this->service->finalizePurchaseOrder($id);

                $result['subtotal'] = $purchaseOrder->subtotal;
                $result['taxes'] = $purchaseOrder->taxes;

                $this->accountExpenseService->create([
                    'amount' => $purchaseOrder->subtotal + $purchaseOrder->taxes,
                    'vendor_id' => $purchaseOrder->vendor_id,
                    'file' => '',
                    'comments' => 'Purchase Order #' . $purchaseOrder->id,
                    'account_expense_category_id' => AccountExpenseCategory::INVENTORY,
                    'store_id' => Auth::user()->storeId(),
                    'user_id' => Auth::user()->userId()
                ]);

                DB::commit();
            }
            catch (Exception $e)
            {
                DB::rollback();

                throw $e;
            }
        }
        else
        {
        }
    }
}