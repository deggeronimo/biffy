<?php

namespace Biffy\Http\Requests\Sale;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateSaleRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'completed' => 'boolean',
            'quote_id' => 'integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}