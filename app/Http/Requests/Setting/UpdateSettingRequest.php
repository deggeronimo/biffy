<?php namespace Biffy\Http\Requests\Setting;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateSettingRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'key' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
} 