<?php

namespace Biffy\Http\Requests\User;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateUserRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'given_name' => 'alpha',
            'family_name' => 'alpha',
            'email' => 'email'
        ];
    }

    public function authorize()
    {
        return true;
    }
}