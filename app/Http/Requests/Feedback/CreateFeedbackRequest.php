<?php namespace Biffy\Http\Requests\Feedback;

use Biffy\Http\Requests\AbstractFormRequest;

class CreateFeedbackRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'feedback_status_id' => 'required|numeric',
            'assigned_to_user_id' => 'required|numeric',
            'customer_id' => 'required|numeric',
            'sale_id' => 'required|numeric',
            'visit_time' => 'required|date',
            'recommend_rating' => 'required|numeric',
            'status_aware_rating' => 'required|numeric',
            'repair_on_time_rating' => 'required|numeric',
            'friendly_rating' => 'required|numeric',
            'communication_rating' => 'required|numeric',
            'overall_rating' => 'required|numeric',
            'main_reason' => 'required',
            'best_part' => 'required',
            'we_improve' => 'required',
            'more_comfortable' => 'required',
            'why_choose_score' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}