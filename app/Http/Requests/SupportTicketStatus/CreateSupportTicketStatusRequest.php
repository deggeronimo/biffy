<?php

namespace Biffy\Http\Requests\SupportTicketStatus;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateSupportTicketStatusRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|alpha'
        ];
    }

    public function authorize()
    {
        return true;
    }
}