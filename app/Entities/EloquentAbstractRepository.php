<?php namespace Biffy\Entities;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class EloquentAbstractRepository implements AbstractRepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    protected $with = [];

    /**
     * @var Builder null
     */
    protected $query = null;

    protected $filters = [];

    protected $sorters = [];

    protected $perPage = null;

    protected $currentPage = null;

    protected $filterBy = [];

    protected $sortBy = null;

    public $selectKeyName = 'id';

    public $selectValueName = 'name';

    public function all()
    {
        return $this->model->all();
    }

    public function reset()
    {
        $this->query = null;

        $this->clearFilters();

        return $this;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function find($id)
    {
        $query = $this->make();

        return $query->findOrFail($id);
    }

    public function with(array $with)
    {
        $this->with = array_merge($this->with, $with);

        return $this;
    }

    public function findFirstBy($key, $value, $operator = '=')
    {
        $query = $this->make();

        return $query->where($key, $operator, $value)->first();
    }

    public function findAllBy($key, $value, $operator = '=')
    {
        $query = $this->make();

        return $query->where($key, $operator, $value)->get();
    }

    public function firstByAttributes($attributes)
    {
        return $this->model->where($attributes)->first();
    }

    public function firstOrCreate(array $attributes)
    {
        return $this->model->firstOrCreate($attributes);
    }

    public function has($relation)
    {
        $query = $this->make();

        return $query->has($relation)->get();
    }

    public function create($attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, $attributes)
    {
        $item = $this->model->findOrFail($id);
 
        /** @var \Illuminate\Database\Eloquent\Model $item */
        return $item->update($attributes);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    protected function make()
    {
        if (count($this->model->getStrings()))
        {
            $this->query = $this->model->strings();
            $this->query->with($this->with);
        }
        else
        {
            $this->query = $this->model->with($this->with);
        }

        return $this->query;
    }

    protected function makeWithStoreUser($storeId, $userId)
    {
        $this->query = $this->model->with($this->with)->where('store_id', '=', $storeId)->where('user_id', '=', $userId);
        return $this->query;
    }

    protected function getOffset($page, $perPage)
    {
        return ($page - 1) * $perPage;
    }

    public function paginate($perPage, $currentPage = 1)
    {
        $this->perPage = $perPage;

        $this->currentPage = $currentPage;

        return $this;
    }

    public function filterBy($filters)
    {
        if (is_null($filters)) {
            $filters = [];
        }

        $this->filterBy = array_merge($filters, $this->filterBy);

        return $this;
    }

    public function addFilter($filterKey, $filterValue)
    {
        $this->filterBy[$filterKey] = $filterValue;

        return $this;
    }

    public function clearFilters()
    {
        $this->filterBy = [];

        return $this;
    }

    public function clearQuery()
    {
        $this->query = null;

        return $this;
    }

    public function sortBy($sorters)
    {
        $this->sortBy = $sorters;

        return $this;
    }

    protected function applyFilters()
    {
        if( is_array($this->filterBy) )
        {
            foreach($this->filterBy as $key => $value)
            {
                if(strlen($key) > 0 && strlen($value) > 0)
                {
                    if(array_key_exists($key, $this->filters))
                    {
                        if(is_array($this->filters[$key]))
                        {
                            $count = count($this->filters[$key]);
                            if($count)
                            {
                                $where = $this->filters[$key][0];
                                $params = [];
                                for($i=1; $i < count($this->filters[$key]); $i++)
                                {
                                    $params[] = str_replace(':value', $value, $this->filters[$key][$i]);
                                }
                                $this->query = $this->query->whereRaw($where, $params);
                            }
                        }
                    }
                }
            }
        }
    }

    //@todo This functions looses the preference order of sorted fields
    protected function applySorters()
    {
        if( is_array($this->sortBy) )
        {
            foreach($this->sortBy as $key => $value)
            {
                if(strlen($key) > 0 && strlen($value) > 0)
                {
                    if( array_key_exists($key, $this->sorters) )
                    {
                        if(array_key_exists($value, $this->sorters[$key]))
                        {
                            $this->query = $this->query->orderByRaw($this->sorters[$key][$value][0] . ' ' . $this->sorters[$key][$value][1]);
                        }
                        else
                        {
                            $this->query = $this->query->orderBy($key, $value);
                        }
                    }
                }
            }
        }
    }

    protected function preGet()
    {
    }

    public function get($columns = ['*'])
    {
        if (is_null($this->query))
        {
            $this->make();
        }

        $this->preGet();

        $this->applyFilters();

        if (is_null($this->perPage))
        {
            $this->applySorters();

            return $this->query->get($columns);
        }
        else
        {
            $perPage = (int) $this->perPage ?: 10;
            $currentPage = $this->currentPage ?: 1;

            $count = $this->query->count();

            $this->applySorters();

            $items = $this->query->skip(($currentPage - 1) * $perPage)->limit($perPage)->get($columns);

            return new LengthAwarePaginator($items, $count, $this->perPage);
        }
    }

    public function getModelStrings()
    {
        return $this->model->getStrings();
    }
}