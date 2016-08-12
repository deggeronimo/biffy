<?php

namespace Biffy\Http\Requests\FileCategory;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateFileCategoryRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|alpha'
        ];
    }

    public function authorize()
    {
        return true;
    }
}