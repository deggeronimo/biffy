<?php namespace Biffy\Http\Requests\ChecklistFunction;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateChecklistFunctionRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}