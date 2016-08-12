<?php namespace Biffy\Entities\Feedback;

use Biffy\Entities\AbstractEntity;

class Feedback extends AbstractEntity
{
    protected $fillable = [
        'feedback_status_id',
        'assigned_to_user_id',
        'customer_id',
        'sale_id',
        'visit_time',
        'recommend_rating',
        'status_aware_rating',
        'repair_on_time_rating',
        'friendly_rating',
        'communication_rating',
        'overall_rating',
        'main_reason',
        'best_part',
        'we_improve',
        'more_comfortable',
        'why_choose_score'
    ];

    public function assignedTo()
    {
        return $this->belongsTo('Biffy\Entities\User\User', 'assigned_to_user_id');
    }

    public function customer()
    {
        return $this->belongsTo('Biffy\Entities\Customer\Customer');
    }

    public function feedbackCallLogs()
    {
        return $this->hasMany('Biffy\Entities\FeedbackCallLog\FeedbackCallLog');
    }

    public function feedbackDocs()
    {
        return $this->hasMany('Biffy\Entities\FeedbackDoc\FeedbackDoc');
    }

    public function feedbackNotes()
    {
        return $this->hasMany('Biffy\Entities\FeedbackNote\FeedbackNote');
    }

    public function feedbackStatus()
    {
        return $this->belongsTo('Biffy\Entities\FeedbackStatus\FeedbackStatus');
    }

    public function sale()
    {
        return $this->belongsTo('Biffy\Entities\Sale\Sale');
    }
}