<?php

namespace Biffy\Http\Requests\Company;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateCompanyRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'address_line_1' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'discount' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}