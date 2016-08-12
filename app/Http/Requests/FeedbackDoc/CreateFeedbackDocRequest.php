<?php namespace Biffy\Http\Requests\FeedbackDoc;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateFeedbackDocRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'file' => 'required',
            'feedback_doctype_id' => 'required|numeric',
            'description' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}