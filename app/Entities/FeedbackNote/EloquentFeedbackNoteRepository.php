<?php namespace Biffy\Entities\FeedbackNote;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentFeedbackNoteRepository extends EloquentAbstractRepository implements FeedbackNoteRepositoryInterface
{
    public function __construct(FeedbackNote $model)
    {
        $this->model = $model;
        $this->with([ 'feedbackStatus', 'user' ]);
    }
}