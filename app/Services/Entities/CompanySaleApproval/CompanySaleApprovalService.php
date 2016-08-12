<?php namespace Biffy\Services\Entities\CompanySaleApproval;

use Biffy\Entities\CompanySaleApproval\CompanySaleApprovalRepositoryInterface;
use Biffy\Entities\WorkOrderStatus\WorkOrderStatus;
use Biffy\Http\Controllers\Helpers\RandomStringGenerator;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;
use Biffy\Services\Entities\WorkOrderNote\WorkOrderNoteService;

class CompanySaleApprovalService extends Service
{
    use RandomStringGenerator;

    protected $workOrderNoteService;
    protected $workOrderService;

    public function __construct(CompanySaleApprovalRepositoryInterface $repo, WorkOrderNoteService $workOrderNoteService,
                                WorkOrderService $workOrderService)
    {
        $this->repo = $repo;

        $this->workOrderNoteService = $workOrderNoteService;
        $this->workOrderService = $workOrderService;
    }

    public function approve($id, $approvalCode)
    {
        $companySaleApproval = $this->repo->find($id);

        if ($companySaleApproval->approval_code != $approvalCode)
        {
            return false;
        }

        $workOrders = [];

        if (!is_null($companySaleApproval->workorder_id))
        {
            $workOrder = $companySaleApproval->workorder;

            $workOrders[] = $workOrder;
        }
        else if (!is_null($companySaleApproval->sale_id))
        {
            $sale = $companySaleApproval->sale;
            $workOrders = $sale->workOrders->all();
        }

        $company = $companySaleApproval->company;

        foreach ($workOrders as $workOrder)
        {
            $notes = "This Work Order has been approved by {$company->name}.";

            $this->workOrderNoteService->create([
                'public' => true,
                'work_order_id' => $workOrder->id,
                'user_id' => \Auth::user()->userId(),
                'workorder_status_id' => WorkOrderStatus::APPROVED,
                'next_update_time' => date('Y-m-d H:i:s'),
                'notes' => $notes
            ]);

            $this->workOrderService->updateStatus($workOrder, $workOrder->workOrderCache);
        }

        $companySaleApproval->approved = true;
        $companySaleApproval->save();

        return true;
    }

    public function create($attributes)
    {
        $attributes['approval_code'] = $this->randomString();

        return $this->repo->create($attributes);
    }
}