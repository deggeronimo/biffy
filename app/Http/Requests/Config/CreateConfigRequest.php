<?php

namespace Biffy\Http\Requests\Config;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateConfigRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'type' => 'required',
            'extra' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}