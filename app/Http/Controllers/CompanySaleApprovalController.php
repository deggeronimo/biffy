<?php namespace Biffy\Http\Controllers;

use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\CompanySaleApproval\CompanySaleApprovalService;

class CompanySaleApprovalController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;

    public function __construct(CompanySaleApprovalService $service)
    {
        $this->service = $service;
    }

    public function update($companyId, $id, AbstractFormRequest $request)
    {
        $input = $request->all();

        $success = $this->service->approve($id, $input['approval_code']);

        if ($success)
        {
            return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
        }
        else
        {
            return $this->messages('message', 'Not updated!')->statusOk()->respond();
        }
    }
}