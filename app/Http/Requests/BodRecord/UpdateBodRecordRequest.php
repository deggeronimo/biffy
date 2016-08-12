<?php namespace Biffy\Http\Requests\BodRecord;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateBodRecordRequest extends AbstractFormRequest
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