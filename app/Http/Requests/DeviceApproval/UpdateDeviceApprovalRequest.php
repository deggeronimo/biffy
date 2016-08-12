<?php namespace Biffy\Http\Requests\DeviceApproval;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateDeviceApprovalRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'device_name' => '',
            'manufacturer_name' => '',
            'carrier_name' => '',
            'approved' => 'integer|min:0|max:1'
        ];
    }

    public function authorize()
    {
        return true;
    }
}