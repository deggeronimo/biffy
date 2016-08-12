<?php namespace Biffy\Http\Requests\MarketingRun;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateMarketingRunRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
        ];
    }

    public function authorize()
    {
        return true;
    }
}