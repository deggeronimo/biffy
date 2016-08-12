<?php namespace Biffy\Http\Requests\DeviceChecklist;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateDeviceChecklistRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'device_type_id' => 'numeric',
            'checklist_function_id' => 'numeric',
            'item_id' => 'numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}