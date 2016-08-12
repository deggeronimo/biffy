<?php

namespace Biffy\Http\Requests\Lead;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateLeadRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'given_name' => 'alpha',
            'family_name' => 'alpha',
            'phone' => 'alpha_dash',
            'email' => 'email',
            'postal_code' => 'numeric',
            'device' => '',
            'issue' => '',
            'price' => 'numeric',
        ];
    }

    public function authorize()
    {
        return true;
    }
}