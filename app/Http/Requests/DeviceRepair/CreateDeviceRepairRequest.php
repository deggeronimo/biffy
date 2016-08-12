<?php namespace Biffy\Http\Requests\DeviceRepair;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceRepairRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'device_type_id' => 'integer|required',
            'device_repair_type_id' => 'integer|required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}