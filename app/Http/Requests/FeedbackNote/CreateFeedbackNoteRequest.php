<?php namespace Biffy\Http\Requests\FeedbackNote;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateFeedbackNoteRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'feedback_id' => 'required|integer',
            'feedback_status_id' => 'required|integer',
            'notes' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
