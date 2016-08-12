<?php namespace Biffy\Http\Requests\InvoicePayment;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateInvoicePaymentRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'amount' => 'numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}