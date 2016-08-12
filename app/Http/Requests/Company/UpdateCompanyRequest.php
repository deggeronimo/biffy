<?php

namespace Biffy\Http\Requests\Company;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateCompanyRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => '',
            'address_line_1' => '',
            'phone' => '',
            'email' => 'email',
            'discount' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}