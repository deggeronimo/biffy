<?php namespace Biffy\Http\Requests\CompanyInstructions;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateCompanyInstructionsRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'lock_trade_credit' => 'boolean',
            'email_template' => '',
            'display_instructions' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}