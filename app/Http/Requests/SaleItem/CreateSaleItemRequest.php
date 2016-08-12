<?php

namespace Biffy\Http\Requests\SaleItem;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateSaleItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'sale_id' => 'integer',
            'work_order_id' => 'integer',
            'store_item_id' => 'required|integer',
            'company_id' => 'integer',
            'on_receipt' => 'required|boolean',
            'tax_exempt' => 'required|boolean'
        ];
    }

    public function authorize()
    {
        return true;
    }
}