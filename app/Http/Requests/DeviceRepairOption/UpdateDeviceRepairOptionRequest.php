<?php namespace Biffy\Http\Requests\DeviceRepairOption;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateDeviceRepairOptionRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}