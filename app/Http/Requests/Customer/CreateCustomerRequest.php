<?php namespace Biffy\Http\Requests\Customer;

use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Http\Requests\PhoneScrubber;

class CreateCustomerRequest extends AbstractFormRequest
{
    use PhoneScrubber;

    public function rules()
    {
        return [
            'given_name' => 'required|alpha',
            'family_name' => 'required|alpha',
            'phone' => 'required',
            'email' => 'email',
            'address_line_1' => '',
            'address_line_2' => '',
            'postal_code' => '',
            'city' => '',
            'state' => '',
            'country' => '',
            'referral_source' => \StoreConfig::get('require-referral-source') ? 'required' : ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}