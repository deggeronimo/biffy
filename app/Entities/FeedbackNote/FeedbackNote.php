<?php namespace Biffy\Entities\FeedbackNote;

use Biffy\Entities\AbstractEntity;

class FeedbackNote extends AbstractEntity
{
    protected $fillable = [
        'feedback_id',
        'user_id',
        'feedback_status_id',
        'notes'
    ];

    public function feedback()
    {
        return $this->belongsTo('Biffy\Entities\Feedback\Feedback');
    }

    public function feedbackStatus()
    {
        return $this->belongsTo('Biffy\Entities\FeedbackStatus\FeedbackStatus');
    }

    public function user()
    {
        return $this->belongsTo('Biffy\Entities\User\User');
    }
}
