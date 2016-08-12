<?php namespace Biffy\Http\Requests\FeedbackCallLog;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateFeedbackCallLogRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'feedback_id' => 'numeric',
            'notes' => '',
            'who_called'
        ];
    }

    public function authorize()
    {
        return true;
    }
}