<?php namespace Biffy\Http\Requests\Language;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateLanguageRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }
} 