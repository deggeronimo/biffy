<?php namespace Biffy\Entities\FeedbackCallLog;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentFeedbackCallLogRepository extends EloquentAbstractRepository implements FeedbackCallLogRepositoryInterface
{
    public function __construct(FeedbackCallLog $model)
    {
        $this->model = $model;
    }
}