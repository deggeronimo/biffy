<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\RosterRole\RosterRoleRepositoryInterface;

/**
 * Class RosterRoleController
 * @package Biffy\Http\Controllers
 */
class RosterRoleController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var RosterRoleRepositoryInterface
     */
    public $repo;

    /**
     * @param RosterRoleRepositoryInterface $repo
     */
    function __construct(RosterRoleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

}
