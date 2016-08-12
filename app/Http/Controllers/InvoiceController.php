<?php namespace Biffy\Http\Controllers;

use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\Invoice\InvoiceService;
use Biffy\Services\Entities\Sale\SaleService;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;

class InvoiceController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;
    protected $saleService;

    public function __construct(InvoiceService $service, SaleService $saleService, WorkOrderService $workOrderService)
    {
        $this->service = $service;
        $this->saleService = $saleService;
        $this->workOrderService = $workOrderService;
    }

    public function show($id)
    {
        $result = $this->service->find($id);

        $paymentList = $result->invoicePayments;
        $paymentTotal = 0;

        foreach ($paymentList as $payment)
        {
            $paymentTotal += $payment->amount;
        }

        $result->payments = $paymentTotal;
        $result->save();

        return $this->data($result->toArray())->respond();
    }

    public function update($id, AbstractFormRequest $request)
    {
        $input = $request->all();

        if (isset($input['workorder_id']))
        {
            $workOrder = $this->workOrderService->find($input['workorder_id']);
            $input['sale_id'] = $workOrder->sale_id;
        }

        if (isset($input['remove_sale_id']))
        {
            $invoice = $this->service->find($id);
            $sale = $this->saleService->find($input['remove_sale_id']);

            //Verify that this sale is actually for this store!
            if ($sale->store_id != $invoice->store_id)
            {
                return $this->messages('message', 'Not updated!')->statusOk()->respond();
            }

            $invoice->sales()->detach(
                [ $input['remove_sale_id'] ]
            );

            $invoice->subtotal -= $sale->trade_credit;

            $invoice->save();

            $sale = $this->saleService->find($input['remove_sale_id']);
            $sale->invoice_id = 0;
            $sale->save();

            return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
        }
        else if (isset($input['sale_id']))
        {
            $invoice = $this->service->find($id);
            $sale = $this->saleService->find($input['sale_id']);

            //Verify that this sale is actually for this store!
            if ($sale->store_id != $invoice->store_id)
            {
                return $this->messages('message', 'Not updated!')->statusOk()->respond();
            }

            $invoice->sales()->attach(
                [ $input['sale_id'] ]
            );

            $invoice->subtotal += $sale->trade_credit;

            $invoice->save();

            $sale = $this->saleService->find($input['sale_id']);
            $sale->invoice_id = $invoice->id;
            $sale->save();

            return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
        }
        else
        {
            $success = $this->service->update($id, $input);

            $this->afterUpdate($id, $input);

            if ($success===true)
            {
                return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
            }
            else
            {
                return $this->messages('message', 'Not updated!')->statusOk()->respond();
            }
        }
    }
}