<?php

namespace Biffy\Http\Requests\File;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateFileRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'file_set_id' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}