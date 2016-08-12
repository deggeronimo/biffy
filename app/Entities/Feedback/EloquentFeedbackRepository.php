<?php namespace Biffy\Entities\Feedback;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentFeedbackRepository extends EloquentAbstractRepository implements FeedbackRepositoryInterface
{
    /**
     * @var array
     */
    protected $filters = [
        'customer' => ['customer_id = ?', ':value']
    ];

    public function __construct(Feedback $model)
    {
        $this->model = $model;

        $this->with([ 'customer', 'sale', 'feedbackDocs.feedbackDoctype', 'feedbackNotes.feedbackStatus',
            'feedbackCallLogs', 'feedbackStatus', 'assignedTo' ]);
    }
}