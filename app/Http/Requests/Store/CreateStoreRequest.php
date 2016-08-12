<?php

namespace Biffy\Http\Requests\Store;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateStoreRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'group_id' => 'required|integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}