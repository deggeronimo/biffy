<?php namespace Biffy\Http\Requests\PurchaseItem;

use Biffy\Http\Requests\AbstractFormRequest;

class CreatePurchaseItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'purchase_order_id' => 'required',
            'store_item_id' => 'required',
            'quantity' => 'required',
            'cost' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
} 