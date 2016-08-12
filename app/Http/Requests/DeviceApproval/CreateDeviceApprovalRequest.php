<?php namespace Biffy\Http\Requests\DeviceApproval;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceApprovalRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'device_name' => '',
            'manufacturer_name' => '',
            'carrier_name' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}