<?php namespace Biffy\Entities\FeedbackDoc;

use Biffy\Entities\EloquentAbstractRepository;
use Illuminate\Support\Facades\DB;

class EloquentFeedbackDocRepository extends EloquentAbstractRepository implements FeedbackDocRepositoryInterface
{
    public function __construct(FeedbackDoc $model)
    {
        $this->model = $model;

        $this->with([ 'feedbackDoctype' ]);
    }

    public function getUnassignedFeedbackDocs()
    {
        $query = $this->make();

        return $query->whereRaw('feedback_id IS null')->get();
    }
}