<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\CustomerNote\CustomerNoteService;

/**
 * Class CustomerNoteController
 * @package Biffy\Http\Controllers
 */
class CustomerNoteController extends ApiController
{
    use Helpers\ServiceShowControllerHelper;
    use Helpers\ServiceStoreControllerHelper;

    /**
     * @var CustomerNoteService
     */
    protected $service;

    /**
     * @param CustomerNoteService $service
     */
    public function __construct(CustomerNoteService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $customerId = $this->input('customer_id');

        if (!is_null($customerId))
        {
            $result = $this->service->getNotesForCustomer($customerId);
        }
        else
        {
            $result = [];
        }

        return $this->data($result)->respond();
    }
}