<?php namespace Biffy\Entities\FeedbackStatus;

use Biffy\Entities\AbstractEntity;

class FeedbackStatus extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function feedbacks()
    {
        return $this->hasMany('Biffy\Entities\Feedback\Feedback');
    }

    public function feedbackNote()
    {
        return $this->hasMany('Biffy\Entities\FeedbackNote\FeedbackNote');
    }
}
