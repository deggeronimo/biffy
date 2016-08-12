<?php namespace Biffy\Http\Requests\Pos;

use Biffy\Http\Requests\AbstractFormRequest;

class CreatePosWarrantyRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'notes' => '',
            'next_update' => '',
            'quickdiag' => 'required',
            'itemswithdevice' => 'required',
            'rating' => 'required',
            'warranty_workorder_id' => 'required|integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
