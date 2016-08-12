<?php namespace Biffy\Entities\SupportTicketCategory;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentSupportTicketCategoryRepository
 * @package Biffy\Entities\SupportTicketCategory
 */
class EloquentSupportTicketCategoryRepository extends EloquentAbstractRepository implements SupportTicketCategoryRepositoryInterface
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
        'name' => ['name LIKE ?', '%:value%'],
        'search' => ['name LIKE ?', '%:value%'],
    ];

    /**
     * @param SupportTicketCategory $model
     */
    public function __construct(SupportTicketCategory $model)
    {
        $this->model = $model;
    }

}
