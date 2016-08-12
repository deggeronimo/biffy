<?php

namespace Biffy\Http\Requests\Inventory;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateInventoryRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'store_item_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'cost' => 'required|numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
} 