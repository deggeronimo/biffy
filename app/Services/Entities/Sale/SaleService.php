<?php namespace Biffy\Services\Entities\Sale;

use Biffy\Entities\Sale\SaleRepositoryInterface;
use Biffy\Entities\WorkOrderStatus\WorkOrderStatus;
use Biffy\Services\Entities\Company\CompanyService;
use Biffy\Services\Entities\CompanySaleApproval\CompanySaleApprovalService;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;

/**
 * Class SaleService
 * @package Biffy\Services\Entities\Sale
 */
class SaleService extends Service
{
    protected $companySaleApprovalService;
    protected $companyService;
    protected $workOrderService;

    /**
     * @param SaleRepositoryInterface $repo
     * @param CompanySaleApprovalService $companySaleApprovalService
     * @param CompanyService $companyService
     * @param WorkOrderService $workOrderService
     */
    public function __construct(SaleRepositoryInterface $repo, CompanySaleApprovalService $companySaleApprovalService,
                                CompanyService $companyService, WorkOrderService $workOrderService)
    {
        $this->repo = $repo;

        $this->companySaleApprovalService = $companySaleApprovalService;
        $this->companyService = $companyService;
        $this->workOrderService = $workOrderService;
    }

    public function update($id, $attributes)
    {
        if (isset($attributes['company_id']))
        {
            $sale = $this->repo->find($id);
            $company = $this->companyService->find($attributes['company_id']);

            if (!is_null($company) && !is_null($company->companyInstructions))
            {
                $instructions = $company->companyInstructions;

                $emailTemplate = $instructions->email_template;

                $headers = "From: approvals@ubreakifix.com\r\nReply-To: approvals@ubreakifix.com";

                //TODO: Uncomment this to send the email.  Yes, it works on my machine --cf
                //mail($company->email, 'Work Order Approval', $emailTemplate, $headers);

                foreach ($sale->workOrders as $workOrder)
                {
                    $notes = "Awaiting approval from {$company->name}.";
                    $this->workOrderService->updateStatusTo($workOrder, WorkOrderStatus::AWAITING_APPROVAL, $notes);

                    $companySaleApproval = $this->companySaleApprovalService->create([
                        'company_id' => $attributes['company_id'],
                        'workorder_id' => $workOrder->id
                    ]);
                }

                return parent::update($id, $attributes);
            }
            else
            {
                return [];
            }
        }
        else
        {
            return parent::update($id, $attributes);
        }
    }
}
