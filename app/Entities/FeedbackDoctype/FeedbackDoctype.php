<?php namespace Biffy\Entities\FeedbackDoctype;

use Biffy\Entities\AbstractEntity;

class FeedbackDoctype extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function feedbackDoc()
    {
        return $this->hasMany('Biffy\Entities\FeedbackDoc\FeedbackDoc');
    }
}