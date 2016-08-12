<?php

namespace Biffy\Http\Requests\TimeClock;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateTimeClockRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'time_in' => 'date',
            'time_out' => 'date',
            'clock_type' => 'integer|max:2'
        ];
    }

    public function authorize()
    {
        return true;
    }
}