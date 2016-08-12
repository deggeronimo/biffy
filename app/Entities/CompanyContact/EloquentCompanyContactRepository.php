<?php namespace Biffy\Entities\CompanyContact;

use Biffy\Entities\Company\Company;
use Biffy\Entities\EloquentAbstractChildRepository;

/**
 * Class EloquentCompanyContactRepository
 * @package Biffy\Entities\Company\Contact
 */
class EloquentCompanyContactRepository extends EloquentAbstractChildRepository implements CompanyContactRepositoryInterface
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
        'name' => ['name LIKE ?', '%:value%'],
        'search' => ['email LIKE ? OR name LIKE ?', '%:value%', '%:value%'],
        'company_id' => ['(company_id = ?)', ':value']
    ];

    /**
     * Bryan: I am implementing CompanyContact as a child repo.
     * To fully realize this we need a parent model, child model, child relationship on parent.
     * I have read the code in Service\StoreTax and Repo\PurchaseItem.
     * I also see `stores.taxes` in routes.php to be closest to nested routes but it does not implement complete crud set.
     * I hope you find this interesting, please review:
     * Route: 'companies.contacts'
     * Repo: Contacts based on EloquentAbstractChildRepository
     *
     * PS: I do not see a need of Laravel IOC to resolve models.
     * This appears to be a very advanced use case of breaking the link between a repo and model.
     * Biffy is the first project in my knowledge to do this.
     *
     * @param CompanyContact $model
     * @param Company $parent
     */
    public function __construct(CompanyContact $model, Company $parent)
    {
        $this->model = $model;
        $this->parent = $parent;
        $this->childRelation = 'contacts';
    }
}
