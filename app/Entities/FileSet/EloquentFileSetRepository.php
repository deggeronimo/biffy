<?php namespace Biffy\Entities\FileSet;

use Biffy\Entities\EloquentAbstractChildRepository;

/**
 * Class EloquentFileSetRepository
 * @package Biffy\Entities\FileSet
 */
class EloquentFileSetRepository extends EloquentAbstractChildRepository implements FileSetRepositoryInterface
{

    public $selectValueName = 'list_name';

    /**
     * @var array
     */
    protected $sorters = [
        'name' => [],
        'category' => [
            'asc' => ['(SELECT name from file_categories WHERE file_categories.id=file_sets.file_category_id)', 'asc'],
            'desc' => ['(SELECT name from file_categories WHERE file_categories.id=file_sets.file_category_id)', 'desc']
        ],
    ];

    /**
     * @var array
     */
    protected $filters = [
        'name' => ['name LIKE ?', '%:value%'],
        'file_category_id' => ['file_category_id = ?', '%:value%'],
        'search' => ['name LIKE ? OR description LIKE ?', '%:value%', '%:value%'],
    ];

    /**
     * @param FileSet $model
     */
    public function __construct(FileSet $model)
    {
        $this->model = $model;
    }
}
