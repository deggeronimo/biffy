<?php namespace Biffy\Http\Requests\Invoice;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateInvoiceRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'integer',
            'company_id' => 'integer',
            'details' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}