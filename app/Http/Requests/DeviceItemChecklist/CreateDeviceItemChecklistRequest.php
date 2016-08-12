<?php namespace Biffy\Http\Requests\DeviceItemChecklist;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateDeviceItemChecklistRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'device_type_id' => 'required|numeric',
            'checklist_item_id' => 'required|numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}