<?php

namespace Biffy\Http\Requests\CompanyContact;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateCompanyContactRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => '',
            'phone' => '',
            'email' => 'email',
        ];
    }

    public function authorize()
    {
        return true;
    }
}