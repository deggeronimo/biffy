<?php namespace Biffy\Http\Requests\BodRecord;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateBodRecordRequest extends AbstractFormRequest
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