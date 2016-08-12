<?php namespace Biffy\Http\Requests\DeviceRepairType;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceRepairTypeRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'image_overlay' => '',
            'class' => '',
            'sort_order' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}