<?php namespace Biffy\Http\Requests\WebsiteFilterGroup;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateWebsiteFilterGroupRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'sort_order',
            'portal_filter'
        ];
    }

    public function authorize()
    {
        return true;
    }
}