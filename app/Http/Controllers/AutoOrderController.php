<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\PurchaseItem\PurchaseItemRepositoryInterface;
use Biffy\Entities\PurchaseOrder\PurchaseOrderRepositoryInterface;
use Biffy\Http\Controllers\Reports\InventoryReporter;
use DB;
use Illuminate\Support\Facades\Auth;

class AutoOrderController extends ApiController
{
    protected $reporter;

    protected $purchaseItemRepo;
    protected $purchaseOrderRepo;

    public function __construct(InventoryReporter $reporter, PurchaseItemRepositoryInterface $purchaseItemRepo,
                                PurchaseOrderRepositoryInterface $purchaseOrderRepo)
    {
        $this->reporter = $reporter;

        $this->purchaseItemRepo = $purchaseItemRepo;
        $this->purchaseOrderRepo = $purchaseOrderRepo;
    }

    public function index()
    {
        DB::beginTransaction();

        $storeId = Auth::user()->storeId();

        $startTime = date('Y-m-d H:i:s', $this->reporter->getMidnight28DaysAgo());
        $endTime = date('Y-m-d H:i:s', $this->reporter->getMidnightTonight());

        $saleItemReport = $this->reporter->generateDetailedReport($storeId, $startTime, $endTime);

        $purchaseOrder = $this->purchaseOrderRepo->create([
            'currency_rate' => '1.0',
            'vendor_id' => 1,
            'store_id' => $storeId
        ]);

        foreach ($saleItemReport as $saleItem)
        {
            if ($saleItem['recommend'] > 0)
            {
                $purchaseItem = $this->purchaseItemRepo->create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'store_item_id' => $saleItem['store_item_id'],
                    'quantity' => $saleItem['recommend'],
                    'cost' => $saleItem['item']['distro_price']
                ]);
            }
        }

        DB::commit();

        return $this->data($purchaseOrder->toArray())->statusOk()->respond();
    }
}