<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\SupportTicketStatus\SupportTicketStatusRepositoryInterface;

/**
 * Class SupportTicketStatusController
 * @package Biffy\Http\Controllers
 */
class SupportTicketStatusController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var SupportTicketStatusRepositoryInterface
     */
    public $repo;

    /**
     * @param SupportTicketStatusRepositoryInterface $repo
     */
    function __construct(SupportTicketStatusRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

}