<?php

namespace Biffy\Http\Requests\Customer;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateCustomerRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'given_name' => 'alpha',
            'family_name' => 'alpha',
            'phone' => '',
            'email' => 'email',
            'address_line_1' => '',
            'address_line_2' => '',
            'postal_code' => '',
            'city' => '',
            'state' => '',
            'country' => '',
            'referral_source' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}