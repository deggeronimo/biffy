<?php namespace Biffy\Entities\RosterRole;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentRosterRoleRepository
 * @package Biffy\Entities\RosterRole
 */
class EloquentRosterRoleRepository extends EloquentAbstractRepository implements RosterRoleRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'category' => [],
        'name' => [],
    ];

    /**
     * @var array
     */
    protected $filters = [
        'id' => ['id = ?', ':value'],
        'category' => ['category LIKE ?', '%:value%'],
        'name' => ['name LIKE ?', '%:value%'],
        'search' => ['category LIKE ? OR name LIKE ?', '%:value%', '%:value%'],
    ];

    /**
     * @param RosterRole $model
     */
    public function __construct(RosterRole $model)
    {
        $this->model = $model;
    }

}
