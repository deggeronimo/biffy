<?php namespace Biffy\Http\Requests\MarketingLocation;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateMarketingLocationRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'marketing_location_type_id' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required',
            'phone' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}