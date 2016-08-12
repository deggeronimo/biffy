<?php namespace Biffy\Http\Requests\WebsiteFilter;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateWebsiteFilterRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'filter_group_id',
            'portal_filter_id',
            'sort_order'
        ];
    }

    public function authorize()
    {
        return true;
    }
}