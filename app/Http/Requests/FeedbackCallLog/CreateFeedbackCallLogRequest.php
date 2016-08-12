<?php namespace Biffy\Http\Requests\FeedbackCallLog;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateFeedbackCallLogRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'feedback_id' => 'required|numeric',
            'notes' => 'required',
            'who_called' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
