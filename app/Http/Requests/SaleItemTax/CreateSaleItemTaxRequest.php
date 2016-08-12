<?php

namespace Biffy\Http\Requests\SaleItemTax;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateSaleItemTaxRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'store_id' => 'required|integer',
            'config_id' => 'required|integer',
            'value' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}