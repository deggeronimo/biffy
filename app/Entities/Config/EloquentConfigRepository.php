<?php namespace Biffy\Entities\Config;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentConfigRepository
 * @package Biffy\Entities\Config
 */
class EloquentConfigRepository extends EloquentAbstractRepository implements ConfigRepositoryInterface
{
    /**
     * @param Config $model
     */
    public function __construct(Config $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function listNames()
    {
        return $this->make()->lists('key', 'id');
    }

    public function dataForCaching()
    {
        return $this->make()->select('id', 'name', 'key', 'type', 'extra', 'default')->get();
    }
}