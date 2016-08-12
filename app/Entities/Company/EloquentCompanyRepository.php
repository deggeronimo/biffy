<?php namespace Biffy\Entities\Company;

use Biffy\Entities\CompanyInstructions\CompanyInstructions;
use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentLeadRepository
 * @package Biffy\Entities\Company
 */
class EloquentCompanyRepository extends EloquentAbstractRepository implements CompanyRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'name' => [],
        'email' => [],
        'phone' => [],
    ];

    /**
     * @var array
     */
    protected $filters = [
        'id' => ['id = ?', ':value'],
        'name' => ['name LIKE ?', '%:value%'],
        'search' => ['email LIKE ? OR name LIKE ?', '%:value%', '%:value%'],
    ];

    protected $companyInstructionsModel;

    /**
     * @param Company $model
     * @param CompanyInstructions $companyInstructionsModel
     */
    public function __construct(Company $model, CompanyInstructions $companyInstructionsModel)
    {
        $this->model = $model;

        $this->companyInstructionsModel = $companyInstructionsModel;
    }

    public function create($attributes)
    {
        $company = parent::create($attributes);

        $this->companyInstructionsModel->create(['company_id' => $company->id]);

        return $company;
    }
}
