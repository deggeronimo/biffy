<?php

namespace Biffy\Http\Requests\Vendor;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateVendorRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'alpha',
            'account_number' => 'numeric',
            'contact_name' => '',
            'contact_phone' => 'alpha_dash',
            'global' => 'boolean',
            'store_id' => 'integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}