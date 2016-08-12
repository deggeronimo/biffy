<?php namespace Biffy\Http\Requests\CompanySaleApproval;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateCompanySaleApprovalRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'approval_code' => 'alpha_num'
        ];
    }

    public function authorize()
    {
        return true;
    }
}