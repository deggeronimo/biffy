<?php

namespace Biffy\Http\Requests\StoreItem;

use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\Item\ItemService;

class CreateStoreItemRequest extends AbstractFormRequest
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function rules()
    {
        return [
            'item_id' => 'required|integer'
        ];
    }

    public function authorize()
    {
        $input = $this->all();

        $item = $this->itemService->find($input['item_id']);

        if ($item->global == '1')
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}