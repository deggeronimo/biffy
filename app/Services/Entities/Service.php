<?php namespace Biffy\Services\Entities;

use Biffy\Commands\AbstractCommand;
use Biffy\Entities\AbstractRepositoryInterface;
use Biffy\Entities\EloquentAbstractChildRepository;

/**
 * Class Service
 * @package Biffy\Services\Entities
 */
abstract class Service
{
    /**
     * The data required by the service
     *
     * @var string $commands
     */
    protected $commands = [];

    /**
     * @var AbstractRepositoryInterface $repo
     */
    protected $repo;

    /**
     * Executes the command list, or fails as a whole
     */
    public function execute()
    {
        $result = [];

        try
        {
            $this->doExecute();
        }
        catch (CommandFailedException $e)
        {
            $result = [
                'error' => $e->getMessage()
            ];
        }

        return $result;
    }

    /**
     * Executes the command list, or fails as a whole
     *
     * @throws CommandFailedException
     */
    private function doExecute()
    {
        $i = 0;
        try
        {
            for (; $i < count($this->commands); $i ++)
            {
                \Command::execute($this->commands[$i]);
            }
        }
        catch (CommandFailedException $e)
        {
            $this->rollback($i);

            throw $e;
        }
    }

    /**
     * @param AbstractCommand|array $command The name of the CommandHandler class
     */
    public function register($command)
    {
        if (is_array($command))
        {
            $this->commands = array_merge($this->commands, $command);
        }
        else
        {
            $this->commands[] = $command;
        }
    }

    /**
     * @param int $count The number of commands to roll back
     *
     * @throws RollbackFailedException
     */
    private function rollback($count)
    {
        try
        {
            for ($i = $count - 1; $i >= 0; $i --)
            {
                \Command::rollback($this->commands[$i]);
            }
        }
        catch (CommandFailedException $e)
        {
            throw new RollbackFailedException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @deprecated Use find instead
     * @param $id
     * @return
     */
    public function getModelByID($id)
    {
        return $this->find($id);
    }

    public function create($attributes)
    {
        return $this->repo->create($attributes);
    }

    /**
     * @deprecated Use create instead
     */
    public function createModel($attributes)
    {
        return $this->repo->create($attributes);
    }

    public function deleteModelByID($id)
    {
        $this->repo->delete($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->repo->all();
    }

    public function get()
    {
        return $this->repo->get();
    }

    public function getIndex($count, $page, $filter, $sorting)
    {
        return $this->repo->paginate($count, $page)->filterBy($filter)->sortBy($sorting)->get();
    }

    public function getList($filter, $sorting)
    {
        return $this->inStore()->repo->filterBy($filter)->sortBy($sorting)->get();
    }

    public function reset()
    {
        $this->repo->reset();

        return $this;
    }

    /**
     * @param int $id
     */
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /**
     * @deprecated Use find instead
     * @param int $id
     */
    public function show($id)
    {
        return $this->find($id);
    }

    /**
     * @deprecated Use create instead

     * @param array $attributes
     * @return mixed
     */
    public function store($attributes)
    {
        return $this->repo->create($attributes);
    }

    /**
     * @param int $id
     * @param array $attributes
     *
     * @return boolean
     */
    public function update($id, $attributes)
    {
        return $this->repo->update($id, $attributes);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function destroy($id)
    {
        $this->repo->delete($id);

        return [];
    }

    public function clearFilters()
    {
        $this->repo->clearFilters();

        return $this;
    }

    public function clearQuery()
    {
        $this->repo->clearQuery();

        return $this;
    }

    public function filterBy($filter)
    {
        $this->repo->filterBy($filter);

        return $this;
    }

    /**
     * @param $sorters
     *
     * @return mixed
     */
    public function sortBy($sorters)
    {
        $this->repo->sortBy($sorters);

        return $this;
    }

    public function findAllBy($key, $value, $operator = '=')
    {
        return $this->repo->findAllBy($key, $value, $operator);
    }

    public function paginate($perPage, $currentPage = 1)
    {
        $this->repo->paginate($perPage, $currentPage);

        return $this;
    }

    public function findFirstBy($key, $value, $operator = '=')
    {
        return $this->repo->findFirstBy($key, $value, $operator);
    }

    /**
     * @param int|null $storeId
     * @return $this
     */
    public function inStore($storeId = null)
    {
        if (is_null($storeId))
        {
            $storeId = \Auth::user()->storeId();
        }

        $this->repo->addFilter('store_id', $storeId);

        return $this;
    }

    public function select()
    {
        $args = func_get_args();

        if($this->repo instanceof EloquentAbstractChildRepository)
        {
            $this->repo->parentId(array_pop($args) ?: null);
        }

        $result = $this->repo->get();
//            ->filterBy($this->input('filter'))
//            ->sortBy($this->input('sorting'))->get();

        $keyName = $this->repo->selectKeyName;
        $valueName = $this->repo->selectValueName;

        $data = [];
        foreach ($result as $item)
        {
            $data[] = [$keyName => $item->$keyName, $valueName => $item->$valueName];
        }

        return $data;
    }

    protected function languageStringUpdated($id, $attributeName, $value, $languageId)
    {
    }

    public static function nameToSeo($name)
    {
        $seo = str_replace(' ', '-', $name);
        $seo = preg_replace('/[^A-Za-z0-9\-]/', '', $seo);
        $seo = strtolower($seo);
        $seo = str_replace('--', '-', $seo);

        return $seo;
    }
}