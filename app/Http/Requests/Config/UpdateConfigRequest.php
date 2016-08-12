<?php

namespace Biffy\Http\Requests\Config;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateConfigRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => '',
            'type' => '',
            'extra' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}