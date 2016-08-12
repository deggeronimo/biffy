<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\PurchaseItem\PurchaseItemService;

/**
 * Class PurchaseItemController
 * @package Biffy\Http\Controllers
 */
class PurchaseItemController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var PurchaseItemService
     */
    protected $service;

    /**
     * @param PurchaseItemService $service
     */
    public function __construct(PurchaseItemService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $filterBy = $this->input('filter');

        if (!is_null($filterBy))
        {
            $result = $this->service->getItemsWithPurchaseOrder($filterBy);
        }
        else
        {
            $result = [];
        }

        return $this->data($result)->statusOk()->respond();
    }
}