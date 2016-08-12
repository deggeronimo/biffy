<?php

namespace Biffy\Http\Requests\WorkOrderNote;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateWorkOrderNoteRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'public' => 'boolean',
            'work_order_id' => 'integer',
            'workorder_status_id' => 'integer',
            'action_id' => 'integer',
            'next_update_time' => 'date',
            'notes' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}