<?php namespace Biffy\Entities\BoardCategory;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentBoardCategoryRepository extends EloquentAbstractRepository implements BoardCategoryRepositoryInterface
{
    public function __construct(BoardCategory $model)
    {
        $this->model = $model;
    }

    public function categoryTree()
    {
        return $this->model->all()->toTree()->toArray();
    }

    public function addCategory($parentId, $data)
    {
        $node = $this->model->create($data);
        if (!is_null($parentId)) {
            $this->model->find($parentId)->append($node);
        }
        return $node;
    }

    public function getCategories()
    {
        return $this->with(['children'])->make()->defaultOrder()->whereIsRoot()->get();
    }

    public function getBoard($id, $page, $perPage, $sort)
    {
        $offset = $this->getOffset($page, $perPage);
        return $this->with(['children' => function ($query) {
                $query->defaultOrder();
            }, 'parent', 'threads' => function ($query) use ($perPage, $offset, $sort) {
                /** @var $query \Illuminate\Database\Query\Builder */
                $query = $query->orderBy('sticky', 'desc');

                switch ($sort) {
                    case 'rep':
                        $query = $query->with(['first_post' => function ($query) {
                                /** @var $query \Illuminate\Database\Query\Builder */
                                $query->orderBy('rep', 'desc');
                            }]);
                        break;
                    case 'time':
                    default:
                        $query = $query->latest('latest_post');
                        break;
                }

                $query->limit($perPage)->offset($offset)->with('user');
            }])->make()->where('id', '=', $id)->get()->first();
    }

    public function getCategory($id)
    {
        return $this->find($id);
    }

    public function updateCategory($id, $data)
    {
        $category = $this->find($id);
        $category->name = $data['name'];
        $category->parent_id = $data['parent_id'];
        $category->save();
    }
}