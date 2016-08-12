<?php namespace Biffy\Entities\FeedbackCallLog;

use Biffy\Entities\AbstractEntity;

class FeedbackCallLog extends AbstractEntity
{
    protected $fillable = [
        'feedback_id',
        'notes',
        'who_called'
    ];

    public function feedback()
    {
        return $this->belongsTo('Biffy\Entities\Feedback\Feedback');
    }
}