<?php

namespace Biffy\Http\Requests\StoreTax;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateStoreTaxRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'store_id' => 'required|integer',
            'name' => 'required',
            'percentage' => 'required',
            'active' => 'required|boolean'
        ];
    }

    public function authorize()
    {
        return true;
    }
}