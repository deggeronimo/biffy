<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Item\ItemService;

class MissingItemController extends ApiController
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * @return mixed
     */
    public function index($search)
    {
        $data = $this->itemService->getUnattachedItems($search);

        return $this->data($data)->respond();
    }
}