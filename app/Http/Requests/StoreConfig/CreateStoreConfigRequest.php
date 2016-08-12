<?php

namespace Biffy\Http\Requests\StoreConfig;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateStoreConfigRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'store_id' => 'required|integer',
            'config_id' => 'required|integer',
            'value' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}