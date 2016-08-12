<?php namespace Biffy\Http\Requests\ReceiveItem;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateReceiveItemRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'quantity' => 'integer|required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
