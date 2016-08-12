<?php namespace Biffy\Http\Requests\DeviceRepair;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateDeviceRepairRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'item_id' => '',
            'image' => '',
            'tag' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}