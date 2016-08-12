<?php namespace Biffy\Http\Requests\ChecklistFunction;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateChecklistFunctionRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}