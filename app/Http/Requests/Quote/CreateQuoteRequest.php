<?php namespace Biffy\Http\Requests\Quote;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateQuoteRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'integer|required',
            'subtotal' => 'required',
            'taxes' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}