<?php

namespace Biffy\Http\Requests\CompanyContact;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateCompanyContactRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => '',
            'phone' => 'alpha_dash',
            'email' => 'email'
        ];
    }

    public function authorize()
    {
        return true;
    }
}