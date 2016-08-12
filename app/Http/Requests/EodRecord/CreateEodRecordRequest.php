<?php namespace Biffy\Http\Requests\EodRecord;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateEodRecordRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'checklist' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}