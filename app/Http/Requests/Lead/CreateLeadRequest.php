<?php

namespace Biffy\Http\Requests\Lead;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateLeadRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'given_name' => 'required|alpha',
            'family_name' => 'required|alpha',
            'phone' => 'required|alpha_dash',
            'email' => 'required|email',
            'postal_code' => 'required',
            'device' => 'required',
            'issue' => '',
            'price' => 'numeric',
        ];
    }

    public function authorize()
    {
        return true;
    }
}