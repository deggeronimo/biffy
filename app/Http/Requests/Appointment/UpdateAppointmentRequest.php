<?php namespace Biffy\Http\Requests\Appointment;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateAppointmentRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'issue' => '',
            'appointment_status_id' => 'numeric',
            'customer_id' => 'numeric',
            'time' => 'numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}