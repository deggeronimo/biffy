<?php namespace Biffy\Http\Requests\DeviceFamily;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateDeviceFamilyRequest extends AbstractFormRequest
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