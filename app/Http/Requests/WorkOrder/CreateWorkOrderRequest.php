<?php

namespace Biffy\Http\Requests\WorkOrder;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateWorkOrderRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'notes' => '',
            'next_update' => '',
            'device_id' => 'integer|required',
            'sale_id' => 'integer|required',
            'workorder_status_id' => 'integer',
            'quickdiag' => 'required',
            'itemswithdevice' => 'required',
            'rating' => 'required',
            'warranty_workorder_id' => 'integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}