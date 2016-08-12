<?php

namespace Biffy\Http\Requests\DeviceStatus;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateDeviceStatusRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'status' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}