<?php namespace Biffy\Http\Requests\LanguageString;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateLanguageStringRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'strings'
        ];
    }

    public function authorize()
    {
        return true;
    }
}