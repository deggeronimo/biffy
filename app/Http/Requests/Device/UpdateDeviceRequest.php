<?php

namespace Biffy\Http\Requests\Device;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateDeviceRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => '',
            'passcode' => '',
            'serial' => '',
            'serial_type' => '',
            'customer_id' => 'integer',
            'device_type_id' => 'integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}