<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Services\Entities\User\UserService;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;
use Biffy\Services\Entities\WorkOrderNote\WorkOrderNoteService;
use Illuminate\Support\Facades\Auth;

/**
 * Class WorkOrderController
 * @package Biffy\Http\Controllers
 */
class WorkOrderController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var WorkOrderService
     */
    protected $service;

    protected $workOrderNoteService;

    /**
     * @param WorkOrderService $service
     * @param UserService $userService
     * @param WorkOrderNoteService $workOrderNoteService
     */
    function __construct(WorkOrderService $service, UserService $userService, WorkOrderNoteService $workOrderNoteService)
    {
        $this->service = $service;

        $this->userService = $userService;
        $this->workOrderNoteService = $workOrderNoteService;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $saleId = $this->input('sale_id');

        if (is_null($saleId))
        {
            $storeId = Auth::user()->storeId();

            $perPage = StoreConfig::get('results-per-page');
            $perPage = (is_null($perPage)?10:$perPage);

            $count = $this->input('count', $perPage);
            $page = $this->input('page', 1);

            $result = $this->service->getWorkOrders($storeId, $count, $page, $this->input('filter'), $this->input('sorting'));

            return $this->data($result->toArray()['data'])->paginator($result)->statusOk()->respond();
        }
        else
        {
            $result = $this->service->findAllBySaleId($saleId);

            return $this->data($result)->statusOk()->respond();
        }
    }

    protected function beforeStore(array $input)
    {
        $input['next_update'] = date('Y-m-d H:i:s', $input['next_update']);

        return $input;
    }
}