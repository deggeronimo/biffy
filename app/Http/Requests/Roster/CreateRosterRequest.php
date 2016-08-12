<?php

namespace Biffy\Http\Requests\Roster;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateRosterRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'store_id' => 'required|numeric',
            'employee_id' => 'required|numeric',
            'start_time' => 'required',
            'time_interval' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}