<?php

namespace Biffy\Http\Requests\Device;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'passcode' => '',
            'serial' => '',
            'serial_type' => 'required',
            'customer_id' => 'required|integer',
            'device_type_id' => 'required|integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}