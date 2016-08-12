<?php

namespace Biffy\Http\Requests\Role;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateRoleRequest extends AbstractFormRequest
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