<?php namespace Biffy\Http\Requests\ChecklistItem;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateChecklistItemRequest extends AbstractFormRequest
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