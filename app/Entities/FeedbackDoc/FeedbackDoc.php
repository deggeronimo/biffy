<?php namespace Biffy\Entities\FeedbackDoc;

use Biffy\Entities\AbstractEntity;

class FeedbackDoc extends AbstractEntity
{
    protected $fillable = [
        'filename',
        'store_id',
        'feedback_id',
        'feedback_doctype_id',
        'description'
    ];

    public function feedback()
    {
        return $this->belongsTo('Biffy\Entities\Feedback\Feedback');
    }

    public function feedbackDoctype()
    {
        return $this->belongsTo('Biffy\Entities\FeedbackDoctype\FeedbackDoctype');
    }

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }
}
