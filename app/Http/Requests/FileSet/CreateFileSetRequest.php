<?php

namespace Biffy\Http\Requests\FileSet;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateFileSetRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|alpha',
            'file_category_id' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}