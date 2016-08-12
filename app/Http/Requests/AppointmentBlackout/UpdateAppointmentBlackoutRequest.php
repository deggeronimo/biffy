<?php namespace Biffy\Http\Requests\AppointmentBlackout;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateAppointmentBlackoutRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'year' => 'numeric',
            'day_of_year' => 'numeric',
            'day_of_week' => 'numeric',
            'hour_of_day' => 'numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}