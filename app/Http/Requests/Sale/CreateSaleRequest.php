<?php

namespace Biffy\Http\Requests\Sale;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateSaleRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}