<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\SupportTicketUpdate\SupportTicketUpdateRepositoryInterface;

/**
 * Class SupportTicketUpdateController
 * @package Biffy\Http\Controllers
 */
class SupportTicketUpdateController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var SupportTicketUpdateRepositoryInterface
     */
    public $repo;

    /**
     * @param SupportTicketUpdateRepositoryInterface $repo
     */
    function __construct(SupportTicketUpdateRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

}