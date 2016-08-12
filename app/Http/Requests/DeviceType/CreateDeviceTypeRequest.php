<?php namespace Biffy\Http\Requests\DeviceType;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceTypeRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'parent_device_type_id' => 'integer',
            'device_manufacturer_id' => 'integer',
            'device_family_id' => 'integer',
            'image' => '',
            'top' => 'boolean',
            'sort_order' => 'integer',
            'product' => 'boolean',
            'status' => 'boolean',
            'model' => '',
            'release_date' => '',
            'filters' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}