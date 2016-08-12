<?php

namespace Biffy\Http\Requests\StoreItem;

use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\StoreItem\StoreItemService;
use Illuminate\Support\Facades\Auth;

class UpdateStoreItemRequest extends AbstractFormRequest
{
    protected $service;

    public function __construct(StoreItemService $service)
    {
        $this->service = $service;
    }
    public function rules()
    {
        return [
            'stock' => 'integer',
            'unit_price' => 'numeric',
            'labor_cost' => 'numeric',
            'last_cost' => 'numeric'
        ];
    }

    public function authorize()
    {
        if (Auth::user()->isAdmin())
        {
            return true;
        }
        else
        {
            $storeId = Auth::user()->storeId();
            $result = $this->service->find($this->route('storeitems'));

            return $result->store_id == $storeId;
        }
    }
}