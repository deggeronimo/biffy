<?php namespace Biffy\Http\Requests\CompanyStoreItem;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateCompanyStoreItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'store_item_id' => 'required',
            'unit_price' => 'required',
            'labor_cost' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}