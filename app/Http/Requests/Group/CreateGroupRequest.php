<?php

namespace Biffy\Http\Requests\Group;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateGroupRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'is_store' => 'boolean'
        ];
    }

    public function authorize()
    {
        return true;
    }
}