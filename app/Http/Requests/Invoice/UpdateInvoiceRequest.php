<?php namespace Biffy\Http\Requests\Invoice;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateInvoiceRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'integer',
            'company_id' => 'integer',
            'details' => '',
            'sale_id' => 'integer',
            'remove_sale_id' => 'integer',
            'workorder_id' => 'integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}