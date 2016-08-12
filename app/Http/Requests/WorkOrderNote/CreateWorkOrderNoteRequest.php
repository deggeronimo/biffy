<?php

namespace Biffy\Http\Requests\WorkOrderNote;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateWorkOrderNoteRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'public' => 'required|boolean',
            'workorder_status_id' => 'required|integer',
            'next_update_time' => 'required|integer',
            'notes' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}