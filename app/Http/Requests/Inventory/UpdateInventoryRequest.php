<?php

namespace Biffy\Http\Requests\Inventory;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateInventoryRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'store_item_id' => 'integer',
            'vendor_id' => 'integer',
            'cost' => 'numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}