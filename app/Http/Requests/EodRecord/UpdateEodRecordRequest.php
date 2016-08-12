<?php namespace Biffy\Http\Requests\EodRecord;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateEodRecordRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'checklist' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}