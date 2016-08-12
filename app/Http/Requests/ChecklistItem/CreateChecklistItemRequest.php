<?php namespace Biffy\Http\Requests\ChecklistItem;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateChecklistItemRequest extends AbstractFormRequest
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