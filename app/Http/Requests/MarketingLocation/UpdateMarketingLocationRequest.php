<?php namespace Biffy\Http\Requests\MarketingLocation;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateMarketingLocationRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => '',
            'marketing_location_type_id' => 'integer',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'address' => '',
            'phone' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}