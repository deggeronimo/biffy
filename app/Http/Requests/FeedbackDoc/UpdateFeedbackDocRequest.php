<?php namespace Biffy\Http\Requests\FeedbackDoc;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateFeedbackDocRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'feedback_id' => 'numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}