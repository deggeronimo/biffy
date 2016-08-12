<?php namespace Biffy\Http\Requests\CustomerNote;

use Biffy\Http\Requests\AbstractFormRequest;

/**
 * Class CreateCustomerNoteRequest
 * @package Biffy\Http\Requests\CustomerNote
 */
class CreateCustomerNoteRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|integer',
            'note' => 'required'
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}