<?php namespace Biffy\Http\Requests\DeviceItemChecklist;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateDeviceItemChecklistRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'device_type_id' => 'numeric',
            'checklist_item_id' => 'numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}