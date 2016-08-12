<?php

namespace Biffy\Http\Requests\Item;

use Biffy\Http\Requests\AbstractFormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'item_number' => 'integer',
            'unit_price' => 'numeric',
            'labor_cost' => 'numeric',
            'distro_price' => 'numeric',
            'name' => '',
            'device_type_id' => 'integer',
            'required' => 'integer'
        ];
    }

    public function authorize()
    {
        return Auth::user()->isAdmin() == '1';
    }
}