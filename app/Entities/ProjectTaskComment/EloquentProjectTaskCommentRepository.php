<?php namespace Biffy\Entities\ProjectTaskComment;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentProjectTaskCommentRepository extends EloquentAbstractRepository implements ProjectTaskCommentRepositoryInterface
{
    public function __construct(ProjectTaskComment $model)
    {
        $this->model = $model;
    }
}