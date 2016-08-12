<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\SupportTicket\SupportTicketRepositoryInterface;

/**
 * Class SupportTicketController
 * @package Biffy\Http\Controllers
 */
class SupportTicketController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var SupportTicketRepositoryInterface
     */
    public $repo;

    /**
     * @param SupportTicketRepositoryInterface $repo
     */
    function __construct(SupportTicketRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

}