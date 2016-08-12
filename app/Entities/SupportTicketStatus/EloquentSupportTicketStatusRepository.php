<?php namespace Biffy\Entities\SupportTicketStatus;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentSupportTicketStatusRepository
 * @package Biffy\Entities\SupportTicketStatus
 */
class EloquentSupportTicketStatusRepository extends EloquentAbstractRepository implements SupportTicketStatusRepositoryInterface
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
     * @param SupportTicketStatus $model
     */
    public function __construct(SupportTicketStatus $model)
    {
        $this->model = $model;
    }

}
