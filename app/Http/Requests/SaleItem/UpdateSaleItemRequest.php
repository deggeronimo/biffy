<?php

namespace Biffy\Http\Requests\SaleItem;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateSaleItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'sale_id' => 'integer',
            'work_order_id' => 'integer',
            'inventory_id' => 'integer',
            'price' => '',
            'labor_cost' => '',
            'discount' => '',
            'on_receipt' => 'boolean',
            'tax_exempt' => 'boolean'
        ];
    }

    public function authorize()
    {
        return true;
    }
}