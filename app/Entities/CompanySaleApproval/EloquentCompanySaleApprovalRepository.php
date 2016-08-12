<?php namespace Biffy\Entities\CompanySaleApproval;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentCompanySaleApprovalRepository extends EloquentAbstractRepository implements CompanySaleApprovalRepositoryInterface
{
    /**
     * @var array
     */
    protected $filters = [
        'approval_code' => ['approval_code = ?', ':value'],
        'company_id' => ['company_id = ?', ':value'],
        'sale_id' => ['sale_id = ?', ':value'],
        'workorder_id' => ['workorder_id = ?', ':value']
    ];

    public function __construct(CompanySaleApproval $model)
    {
        $this->model = $model;

        $this->with([ 'sale', 'sale.workOrders', 'workorder' ]);
    }
}
