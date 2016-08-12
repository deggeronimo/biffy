<?php namespace Biffy\Http\Requests\SalePayment;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateSalePaymentRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'sale_id' => 'required|integer',
            'sale_payment_type_id' => 'required|integer|max:8',
            'amount' => 'required|numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}