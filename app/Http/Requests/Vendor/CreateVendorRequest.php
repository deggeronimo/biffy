<?php

namespace Biffy\Http\Requests\Vendor;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateVendorRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|alpha',
            'account_number' => 'required|numeric',
            'contact_name' => 'required',
            'contact_phone' => 'required|alpha_dash',
            'global' => 'required|boolean'
        ];
    }

    public function authorize()
    {
        return true;
    }
}