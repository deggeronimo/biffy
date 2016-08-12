<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\RecommendedItem\RecommendedItemService;

/**
 * Class RecommendedItemController
 * @package Biffy\Http\Controllers
 */
class RecommendedItemController extends ApiController
{
    /**
     * @param RecommendedItemService $service
     */
    public function __construct(RecommendedItemService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $workOrderId
     * @return mixed
     */
    public function index($workOrderId)
    {
        $result = $this->service->allFromWorkOrder($workOrderId);

        return $this->data($result)->statusOk()->respond();
    }
}