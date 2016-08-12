<?php namespace Biffy\Http\Requests\InvoicePayment;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateInvoicePaymentRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'invoice_id' => 'required|integer',
            'sale_payment_type_id' => 'required|integer|max:8',
            'amount' => 'required|numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}