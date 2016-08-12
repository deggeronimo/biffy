<?php

namespace Biffy\Http\Requests\TimeClock;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateTimeClockRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'clock_type' => 'required|integer|max:2',
            'time_in' => '',
            'time_out' => '',
        ];
    }

    public function authorize()
    {
        return true;
    }
}