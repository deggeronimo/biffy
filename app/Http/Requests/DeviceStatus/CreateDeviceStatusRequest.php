<?php

namespace Biffy\Http\Requests\DeviceStatus;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceStatusRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'status' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}