<?php namespace Biffy\Http\Requests\PurchaseOrder;

use Biffy\Http\Requests\AbstractFormRequest;

class CreatePurchaseOrderRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'currency_rate' => 'numeric|required',
            'shipping_cost' => '',
            'vendor_id' => 'numeric|required',
            'tracking_number' => '',
            'shipping_method' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
} 