<?php

namespace Biffy\Http\Requests\RosterRole;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateRosterRoleRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'category' => 'required|alpha',
            'name' => 'required|alpha'
        ];
    }

    public function authorize()
    {
        return true;
    }
}