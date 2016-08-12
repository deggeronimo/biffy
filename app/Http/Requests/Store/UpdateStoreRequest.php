<?php

namespace Biffy\Http\Requests\Store;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateStoreRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => '',
            'group_id' => 'integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}