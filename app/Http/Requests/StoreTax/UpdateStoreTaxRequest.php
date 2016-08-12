<?php

namespace Biffy\Http\Requests\StoreTax;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateStoreTaxRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}