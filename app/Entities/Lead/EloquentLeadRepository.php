<?php namespace Biffy\Entities\Lead;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentLeadRepository
 * @package Biffy\Entities\Lead
 */
class EloquentLeadRepository extends EloquentAbstractRepository implements LeadRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'email' => [],
        'given_name' => [],
        'family_name' => [],
        'device' => [],
        'phone' => [],
    ];

    /**
     * @var array
     */
    protected $filters = [
        'email' => ['email LIKE ?', '%:value%'],
        'search' => ['email LIKE ? OR given_name LIKE ? OR family_name LIKE ? OR postal_code LIKE ? OR device LIKE ? OR phone LIKE ?', '%:value%', '%:value%', '%:value%', '%:value%', '%:value%', '%:value%'],
    ];

    /**
     * @param Lead $model
     */
    public function __construct(Lead $model)
    {
        $this->model = $model;
    }

}
