<?php

namespace Biffy\Http\Requests\Group;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateGroupRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => '',
            'email' => 'email',
            'is_store' => 'boolean'
        ];
    }

    public function authorize()
    {
        return true;
    }
}