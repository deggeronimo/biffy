<?php namespace Biffy\Entities\CompanyInstructions;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentCompanyInstructionsRepository extends EloquentAbstractRepository implements CompanyInstructionsRepositoryInterface
{
    /**
     * @var array
     */
    protected $filters = [
        'company_id' => ['company_id = ?', ':value']
    ];

    public function __construct(CompanyInstructions $model)
    {
        $this->model = $model;
    }
}