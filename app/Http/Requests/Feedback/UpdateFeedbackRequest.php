<?php namespace Biffy\Http\Requests\Feedback;

use Biffy\Http\Requests\AbstractFormRequest;

class UpdateFeedbackRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'feedback_status_id' => 'numeric',
            'assigned_to_user_id' => 'numeric',
            'customer_id' => 'numeric',
            'sale_id' => 'numeric',
            'visit_time' => 'date',
            'recommend_rating' => 'numeric',
            'status_aware_rating' => 'numeric',
            'repair_on_time_rating' => 'numeric',
            'friendly_rating' => 'numeric',
            'communication_rating' => 'numeric',
            'overall_rating' => 'numeric',
            'main_reason' => '',
            'best_part' => '',
            'we_improve' => '',
            'more_comfortable' => '',
            'why_choose_score' => ''
        ];
    }

    public function authorize()
    {
        return true;
    }
}