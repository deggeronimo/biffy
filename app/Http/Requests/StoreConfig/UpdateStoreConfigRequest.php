<?php

namespace Biffy\Http\Requests\StoreConfig;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateStoreConfigRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'store_id' => 'integer',
            'config_id' => 'integer',
            'value' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}