<?php namespace Biffy\Http\Requests\PurchaseItem;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdatePurchaseItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'purchase_order_id' => '',
            'store_item_id' => '',
            'quantity' => '',
            'cost' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
} 