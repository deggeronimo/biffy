<?php namespace Biffy\Entities;

/**
 * Interface AbstractRepositoryInterface
 * @package Biffy\Entities
 */
interface AbstractRepositoryInterface
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param array $with
     * @return static
     */
    public function with(array $with);

    /**
     * @param $key
     * @param $value
     * @param string $operator
     * @return mixed
     */
    public function findFirstBy($key, $value, $operator = '=');

    /**
     * @param $key
     * @param $value
     * @param string $operator
     * @return mixed
     */
    public function findAllBy($key, $value, $operator = '=');

    /**
     * @param $attributes
     * @return mixed
     */
    public function firstByAttributes($attributes);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function firstOrCreate(array $attributes);

    /**
     * @param $relation
     * @return mixed
     */
    public function has($relation);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes);

    /**
     * @param $id
     * @param $attributes
     * @return mixed
     */
    public function update($id, $attributes);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    public function paginate($perPage, $currentPage = 1);

    /**
     * @param $sorters
     * @return mixed
     */
    public function sortBy($sorters);

    /**
     * @param $filters
     * @return mixed
     */
    public function filterBy($filters);

    /**
     * @param $filterKey
     * @param $filterValue
     * @return mixed
     */
    public function addFilter($filterKey, $filterValue);

    /**
     * @return mixed
     */
    public function clearFilters();

    public function clearQuery();
}