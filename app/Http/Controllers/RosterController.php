<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\Roster\RosterRepositoryInterface;

/**
 * Class RosterController
 * @package Biffy\Http\Controllers
 */
class RosterController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var RosterRepositoryInterface
     */
    public $repo;

    /**
     * @param RosterRepositoryInterface $repo
     */
    function __construct(RosterRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

}
