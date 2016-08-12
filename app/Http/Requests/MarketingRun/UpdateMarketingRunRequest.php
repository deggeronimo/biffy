<?php namespace Biffy\Http\Requests\MarketingRun;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateMarketingRunRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'stopped' => 'boolean',
            'marketing_location_id' => 'integer',
            'marketing_run_type_id' => 'integer',
            'comments' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}