<?php

namespace Biffy\Http\Requests\SupportTicketCategory;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateSupportTicketCategoryRequest extends AbstractFormRequest
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