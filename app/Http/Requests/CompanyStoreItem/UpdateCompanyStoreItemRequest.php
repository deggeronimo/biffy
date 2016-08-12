<?php namespace Biffy\Http\Requests\CompanyStoreItem;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateCompanyStoreItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'unit_price' => '',
            'labor_cost' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}