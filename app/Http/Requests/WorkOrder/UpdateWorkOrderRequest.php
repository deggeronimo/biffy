<?php

namespace Biffy\Http\Requests\WorkOrder;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateWorkOrderRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'notes' => '',
            'next_update' => '',
            'device_id' => 'integer',
            'sale_id' => 'integer',
            'workorder_status_id' => 'integer',
            'quickdiag' => '',
            'itemswithdevice' => '',
            'rating' => 'integer',
            'queue' => 'integer|min:0|max:2',
            'assigned_to_user_id' => 'integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}