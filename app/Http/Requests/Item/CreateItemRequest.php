<?php

namespace Biffy\Http\Requests\Item;

use Biffy\Http\Requests\AbstractFormRequest;
use Illuminate\Support\Facades\Auth;

class CreateItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'item_number' => 'required|integer',
            'unit_price' => 'numeric',
            'labor_cost' => 'numeric',
            'distro_price' => 'numeric',
            'name' => 'required',
            'global' => 'boolean',
            'device_type_id' => 'integer',
            'required' => 'integer'
        ];
    }

    public function authorize()
    {
        if ($this->global == '1')
        {
            return Auth::user()->isAdmin();
        }
        else if ($this->global == '0')
        {
            return true;
        }
        else
        {
            //Only allow global to take the values '0' or '1'
            return false;
        }
    }
}