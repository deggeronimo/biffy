<?php namespace Biffy\Entities\BoardPost;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentBoardPostRepository extends EloquentAbstractRepository implements BoardPostRepositoryInterface
{
    public function __construct(BoardPost $model)
    {
        $this->model = $model;
    }

    public function getPostEdit($id)
    {
        return $this->with(['thread.category'])->find($id);
    }

    public function getPost($id)
    {
        $userIdClosure = function ($query) {
            $query->where('user_id', '=', \Auth::user()->userId());
        };
        return $this->with(['rep_votes' => $userIdClosure, 'user'])->find($id);
    }

    public function countInThread($threadId)
    {
        return $this->make()->where('thread_id', '=', $threadId)->count();
    }
}