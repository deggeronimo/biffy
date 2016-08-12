<?php namespace Biffy\Entities\SupportTicketUpdate;

use Biffy\Entities\SupportTicket\SupportTicket;
use Biffy\Entities\EloquentAbstractChildRepository;

/**
 * Class EloquentSupportTicketUpdateRepository
 * @package Biffy\Entities\SupportTicketUpdate
 */
class EloquentSupportTicketUpdateRepository extends EloquentAbstractChildRepository implements SupportTicketUpdateRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'created_at' => [],
        'id' => [],
    ];

    /**
     * @var array
     */
    protected $filters = [
    ];

    public function __construct(SupportTicketUpdate $model, SupportTicket $parent)
    {
        $this->model = $model;
        $this->parent = $parent;
        $this->childRelation = 'updates';
    }

}
