<?php namespace Biffy\Http\Requests\DeviceRepairOption;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceRepairOptionRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}