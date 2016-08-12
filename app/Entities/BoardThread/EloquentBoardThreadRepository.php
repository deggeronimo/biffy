<?php namespace Biffy\Entities\BoardThread;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentBoardThreadRepository extends EloquentAbstractRepository implements BoardThreadRepositoryInterface
{
    public function __construct(BoardThread $model)
    {
        $this->model = $model;
    }

    public function getThread($id, $page, $perPage, $sort)
    {
        $userIdClosure = function ($query) {
            $query->where('user_id', '=', \Auth::user()->userId());
        };

        $signatureClosure = function ($query) {
            /** @var $query \Illuminate\Database\Query\Builder */
            $query->with(['profile' => function ($query) {
                    /** @var $query \Illuminate\Database\Query\Builder */
                    $query->select(['signature', 'user_id']);
                }]);
        };

        $offset = $this->getOffset($page, $perPage);
        return $this->with(['first_post.rep_votes' => $userIdClosure, 'posts' => function ($query) use ($perPage, $offset, $sort, $userIdClosure, $signatureClosure) {
                /** @var $query \Illuminate\Database\Query\Builder */
                switch ($sort) {
                    case 'rep':
                        $query = $query->orderBy('rep', 'desc');
                        break;
                    case 'time':
                    default:
                        $query = $query->oldest();
                        break;
                }
                $query->limit($perPage)->offset($offset)->with(['user' => $signatureClosure, 'rep_votes' => $userIdClosure]);
            }, 'user' => $signatureClosure, 'category', 'subscriptions' => function ($query) {
                /** @var $query \Illuminate\Database\Query\Builder */
                $query->where('user_id', '=', \Auth::user()->userId());
            }])->find($id);
    }

    public function getThreadForEdit($id)
    {
        return $this->with(['first_post', 'category'])->find($id);
    }

    public function getByFirstPost($postId)
    {
        return $this->make()->where('first_post_id', '=', $postId)->first();
    }

    public function countInCategory($categoryId)
    {
        return $this->make()->where('category_id', '=', $categoryId)->count();
    }

    public function getLatestUnread($userId, $count)
    {
        return $this->with(['user'])->make()->whereDoesntHave('thread_views', function ($query) use ($userId) {
                /** @var $query \Illuminate\Database\Query\Builder */
                $query->where('user_id', '=', $userId)->where('current', '=', 1);
            })->latest('latest_post')->limit($count)->get();
    }
} 