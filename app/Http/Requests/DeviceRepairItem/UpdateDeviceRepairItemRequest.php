<?php namespace Biffy\Http\Requests\DeviceRepairItem;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateDeviceRepairItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
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