<?php namespace Biffy\Http\Requests\Appointment;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateAppointmentRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'issue' => 'required',
            'appointment_status_id' => 'numeric|required',
            'customer_id' => 'numeric|required',
            'time' => 'numeric|required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}