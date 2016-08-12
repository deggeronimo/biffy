<?php namespace Biffy\Entities\FileCategory;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentFileCategoryRepository
 * @package Biffy\Entities\FileCategory
 */
class EloquentFileCategoryRepository extends EloquentAbstractRepository implements FileCategoryRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'name' => [],
    ];

    /**
     * @var array
     */
    protected $filters = [
        'id' => ['id = ?', ':value'],
        'name' => ['name LIKE ?', '%:value%'],
        'search' => ['name LIKE ?', '%:value%'],
    ];

    /**
     * @param FileCategory $model
     */
    public function __construct(FileCategory $model)
    {
        $this->model = $model;
    }

}
