<?php namespace Biffy\Entities\SupportTicket;

use Biffy\Entities\SupportTicketUpdate\SupportTicketUpdate;
use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentSupportTicketRepository
 * @package Biffy\Entities\SupportTicket
 */
class EloquentSupportTicketRepository extends EloquentAbstractRepository implements SupportTicketRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'created_at' => [],
        'id' => [],
        'title' => [],
        'author' => [
            'asc' => ['(SELECT given_name from users WHERE users.id=support_tickets.author_id)', 'asc'],
            'desc' => ['(SELECT given_name from users WHERE users.id=support_tickets.author_id)', 'desc']
        ],
        'assignee' => [
            'asc' => ['(SELECT given_name from users WHERE users.id=support_tickets.user_id)', 'asc'],
            'desc' => ['(SELECT given_name from users WHERE users.id=support_tickets.user_id)', 'desc']
        ],
        'status' => [
            'asc' => ['(SELECT name from support_ticket_statuses WHERE support_ticket_statuses.id=support_tickets.status_id)', 'asc'],
            'desc' => ['(SELECT name from support_ticket_statuses WHERE support_ticket_statuses.id=support_tickets.status_id)', 'desc']
        ],
        'category' => [
            'asc' => ['(SELECT name from support_ticket_categories WHERE support_ticket_categories.id=support_tickets.category_id)', 'asc'],
            'desc' => ['(SELECT name from support_ticket_categories WHERE support_ticket_categories.id=support_tickets.category_id)', 'desc']
        ],
    ];

    /**
     * @var array
     */
    protected $filters = [
        'id' => ['id = ?', ':value'],
        'user_id' => ['user_id = ?', ':value'],
        'status_id' => ['status_id = ?', ':value'],
        'category_id' => ['category_id = ?', ':value'],
        'name' => ['title LIKE ?', '%:value%'],
        'search' => ['title LIKE ?', '%:value%'],
    ];

    /**
     * @param SupportTicket $model
     * @param SupportTicketUpdate $updateModel
     */
    public function __construct(SupportTicket $model, SupportTicketUpdate $updateModel)
    {
        $this->model = $model;
        $this->updateModel = $updateModel;
    }

    /**
     * We override create method on repo to insert a new row in SupportTicketUpdate containing first set of [status,assignee,message]
     */
    public function create($attributes)
    {
        $ticket = $this->model->create($attributes);
        if(array_key_exists('watcher_ids', $attributes) ) $ticket->watcher_ids = $attributes['watcher_ids'];
        $ticket->save();
        $this->updateModel->create([
            'support_ticket_id' => $ticket->id,
            'status_id' => $attributes['status_id'],
            'assignee_id' => isset($attributes['assignee_id']) ? $attributes['assignee_id'] : null,
            'message' => isset($attributes['message']) ? $attributes['message'] : null,
        ]);
        return $ticket;
    }

}
