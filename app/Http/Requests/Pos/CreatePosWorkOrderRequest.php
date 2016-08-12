<?php namespace Biffy\Http\Requests\Pos;

use Biffy\Http\Requests\AbstractFormRequest;

class CreatePosWorkOrderRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'integer',

            'device_id' => 'integer',
            'device_name' => '',
            'device_passcode' => '',
            'device_serial' => '',
            'device_serial_type' => '',
            'device_type_id' => 'integer',

            'sale_id' => 'integer',

            'next_update' => '',
            'notes' => '',
            'quickdiag' => '',
            'itemswithdevice' => '',
            'rating' => 'integer',
            'warranty_workorder_id' => 'integer',

            'item_id_list' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}