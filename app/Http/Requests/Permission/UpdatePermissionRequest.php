<?php

namespace Biffy\Http\Requests\Permission;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdatePermissionRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}