<?php namespace Biffy\Http\Requests\SalePayment;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateSalePaymentRequest extends AbstractFormRequest
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