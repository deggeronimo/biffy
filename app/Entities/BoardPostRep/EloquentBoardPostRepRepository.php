<?php namespace Biffy\Entities\BoardPostRep;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentBoardPostRepRepository extends EloquentAbstractRepository implements BoardPostRepRepositoryInterface
{
    public function __construct(BoardPostRep $model)
    {
        $this->model = $model;
    }

    public function alreadyVoted($postId, $userId)
    {
        return $this->model->where('post_id', '=', $postId)->where('user_id', '=', $userId)->count();
    }
}