<?php

namespace Biffy\Http\Requests\User;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateUserRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'given_name' => 'required|alpha',
            'family_name' => 'required|alpha',
            'email' => 'required|email'
        ];
    }

    public function authorize()
    {
        return true;
    }
}