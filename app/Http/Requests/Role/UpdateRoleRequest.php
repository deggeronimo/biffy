<?php

namespace Biffy\Http\Requests\Role;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateRoleRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'currency_rate' => '',
            'shipping_cost' => '',
            'vendor_id' => '',
            'tracking_number' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}