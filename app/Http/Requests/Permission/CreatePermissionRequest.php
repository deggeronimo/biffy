<?php

namespace Biffy\Http\Requests\Permission;

use Biffy\Http\Requests\AbstractFormRequest;

class CreatePermissionRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}