<?php namespace Biffy\Http\Requests\DeviceRepairItem;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceRepairItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'device_repair_option_id' => 'required|integer',
            'item_id' => 'integer',
            'option_value',
            'image' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}