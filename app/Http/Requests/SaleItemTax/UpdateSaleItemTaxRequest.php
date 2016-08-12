<?php

namespace Biffy\Http\Requests\SaleItemTax;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateSaleItemTaxRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'store_id' => 'integer',
            'config_id' => 'integer',
            'value' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}