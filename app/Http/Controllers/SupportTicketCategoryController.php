<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\SupportTicketCategory\SupportTicketCategoryRepositoryInterface;

/**
 * Class SupportTicketCategoryController
 * @package Biffy\Http\Controllers
 */
class SupportTicketCategoryController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var SupportTicketCategoryRepositoryInterface
     */
    public $repo;

    /**
     * @param SupportTicketCategoryRepositoryInterface $repo
     */
    function __construct(SupportTicketCategoryRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

}