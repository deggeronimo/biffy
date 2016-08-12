<?php namespace Biffy\Http\Requests\DeviceManufacturer;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceManufacturerRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name'
        ];
    }

    public function authorize()
    {
        return true;
    }
}