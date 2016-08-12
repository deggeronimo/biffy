<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Services\Entities\Inventory\InventoryService;

/**
 * Class InventoryController
 * @package Biffy\Http\Controllers
 */
class InventoryController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var InventoryService
     */
    protected $service;

    /**
     * @param InventoryService $service
     */
    function __construct(InventoryService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $perPage = StoreConfig::get('results-per-page');
        $perPage = (is_null($perPage)?10:$perPage);

        $count = $this->input('count', $perPage);
        $page = $this->input('page', 1);

        $filter = $this->input('filter');
        $sorting = $this->input('sorting');

        $result = $this->service
            ->paginate($count, $page)
            ->filterBy($filter)
            ->sortBy($sorting)->get();

        // todo move strings
        $statusStrings = ['', 'In Stock', 'On Transit', 'Back Ordered'];
        $data = array_map(function ($val) use ($statusStrings) {
                $status = $val['status'];
                $val['status'] = '';
                if (!is_null($val['sold_by_user_id'])) {
                    $val['status'] .= 'Sold by ' . $val['sold_by_user']['given_name'] . ' ' . $val['sold_by_user']['family_name'] . '<br>';
                }
                $val['status'] .= $statusStrings[$status];
                return $val;
            }, $result->toArray()['data']);

        return $this->data($data)->paginator($result)->respond();
    }
}