<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\Item\Item;
use Biffy\Services\Entities\Item\ItemService;
use Biffy\Services\Entities\Store\StoreService;
use Biffy\Services\Entities\StoreItem\StoreItemService;

/**
 * Class ItemController
 * @package Biffy\Http\Controllers
 */
class ItemController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var ItemService
     */
    protected $service;

    /**
     * @var StoreService
     */
    protected $storeService;

    /**
     * @var StoreItemService
     */
    protected $storeItemService;

    /**
     * @param ItemService $service
     * @param StoreService $storeService
     * @param StoreItemService $storeItemService
     */
    function __construct(ItemService $service, StoreService $storeService, StoreItemService $storeItemService)
    {
        $this->service = $service;
        $this->storeService = $storeService;
        $this->storeItemService = $storeItemService;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if (!is_null($this->input('all')))
        {
            $result = $this->service->all();

            return $this->data($result->toArray())->respond();
        }
        else
        {
            //@todo Make new config setting for count per page
            $perPage = \StoreConfig::get('results-per-page');
            $perPage = (is_null($perPage)?10:$perPage);

            $count = $this->input('count', $perPage);
            $page = $this->input('page', 1);

            $result = $this->service
                ->paginate($count, $page)
                ->filterBy($this->input('filter'))
                ->sortBy($this->input('sorting'))->get();

            return $this->data($result->toArray()['data'])->paginator($result)->respond();
        }
    }
}