<?php namespace Biffy\Http\Requests\PurchaseOrder;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdatePurchaseOrderRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'currency_rate' => '',
            'shipping_cost' => '',
            'vendor_id' => 'numeric',
            'tracking_number' => '',
            'shipping_method_id' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}