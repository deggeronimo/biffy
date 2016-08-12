<?php

namespace Biffy\Http\Requests\SupportTicketUpdate;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateSupportTicketUpdateRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'support_ticket_id' => 'required|numeric',
        ];
    }

    public function authorize()
    {
        return true;
    }
}