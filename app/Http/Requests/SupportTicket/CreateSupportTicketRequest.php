<?php

namespace Biffy\Http\Requests\SupportTicket;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateSupportTicketRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'status_id' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}