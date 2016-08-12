<?php namespace Biffy\Http\Requests\DeviceChecklist;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceChecklistRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'device_type_id' => 'required|numeric',
            'checklist_function_id' => 'required|numeric',
            'item_id' => 'required|numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}