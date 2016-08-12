<?php namespace Biffy\Services\Entities\WorkOrder;

use Biffy\Entities\WorkOrder\WorkOrderRepositoryInterface;
use Biffy\Entities\WorkOrderCache\WorkOrderCacheRepositoryInterface;
use Biffy\Entities\WorkOrderStatus\WorkOrderStatus;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\WorkOrderNote\WorkOrderNoteService;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class WorkOrderService
 * @package Biffy\Services\Entities\WorkOrder
 */
class WorkOrderService extends Service
{
    protected $workOrderCacheRepo;

    /**
     * @param WorkOrderRepositoryInterface $repo
     * @param WorkOrderCacheRepositoryInterface $workOrderCacheRepo
     * @param WorkOrderNoteService $workOrderNoteService
     */
    public function __construct(WorkOrderRepositoryInterface $repo, WorkOrderCacheRepositoryInterface $workOrderCacheRepo,
                                WorkOrderNoteService $workOrderNoteService)
    {
        $this->repo = $repo;
        $this->workOrderCacheRepo = $workOrderCacheRepo;

        $this->workOrderNoteService = $workOrderNoteService;
    }

    /**
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        return $this->repo->show($id)->toArray();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes)
    {
        $result = $this->repo->create($attributes);

        $this->workOrderCacheRepo->create([
            'work_order_id' => $result->id
        ]);

        return $result;
    }

    /**
     * @param int $id
     * @param array $attributes
     * @return array
     */
    public function update($id, $attributes)
    {
        return $this->repo->update($id, $attributes);
    }

    /**
     * @param int $id
     * @return array
     */
    public function destroy($id)
    {
        $this->repo->delete($id);

        return [];
    }

    public function findAllBySaleId($saleId)
    {
        return $this->repo->with(['device', 'workOrderStatus', 'device.customer'])->findAllBy('sale_id', $saleId)->toArray();
    }

    public function getWorkOrders($storeId, $count, $page, $filter, $sortBy)
    {
        $this->repo->with(['device', 'workOrderStatus', 'device.customer']);
        return $this->inStore($storeId)->paginate($count, $page)->filterBy($filter)->sortBy($sortBy)->get();
    }

    /**
     * @param $storeId
     * @param $count
     * @param $workorderStatus
     * @return mixed
     */
    public function getPaginatedWorkOrderIndexForStoreWithStatus($storeId, $count, $workorderStatus)
    {
        return $this->repo->getPaginatedWorkOrderIndexForStoreWithStatus($storeId, $count, $workorderStatus)->toArray()['data'];
    }

    /**
     * @param $storeId
     * @param $count
     * @param $workorderStatus
     * @param $filterBy
     * @return mixed
     */
    public function getFilteredPaginatedWorkOrderIndexForStoreWithStatus($storeId, $count, $workorderStatus, $filterBy)
    {
        return $this->repo->getFilteredPaginatedWorkOrderIndexForStoreWithStatus($storeId, $count, $workorderStatus, $filterBy)
            ->toArray()['data'];
    }

    /**
     * @param $storeId
     * @param int $count
     *
     * @returns LengthAwarePaginator
     */
    public function getPaginatedWorkOrderIndexForStore($storeId, $count)
    {
        return $this->repo->getPaginatedWorkOrderIndexForStore($storeId, $count)->toArray()['data'];
    }

    public function updateStatus($workOrder, $workOrderCache)
    {
        $oldStatus = $workOrder->workorder_status_id;

        if ($workOrderCache->needs_to_order_parts > 0)
        {
            $workOrder->workorder_status_id = WorkOrderStatus::NEED_TO_ORDER_PARTS;
            $notes = "There are parts that need to be ordered.<br/>This status was automatically generated.";
        }
        else if ($workOrderCache->awaiting_parts > 0)
        {
            $workOrder->workorder_status_id = WorkOrderStatus::AWAITING_PARTS;
            $notes = "The parts for this Work Order have been ordered.<br/>This status was automatically generated.";
        }
        else
        {
            $workOrder->workorder_status_id = WorkOrderStatus::AWAITING_REPAIR;
            $notes = "The parts for this Work Order have arrived in the store!  Your device is now awaiting repair.<br/>This status was automatically generated.";
        }

        if ($workOrder->workorder_status_id != $oldStatus)
        {
            $this->workOrderNoteService->insertWorkOrderNote($workOrder->id, [
                    'workorder_status_id' => $workOrder->workorder_status_id,
                    'notes' => $notes,
                    'user_id' => \Auth::user()->userId(),
                    'public' => false,
                    'auto' => true
                ]);

            $workOrder->save();
        }
    }

    public function updateStatusTo($workOrder, $newStatus, $notes)
    {
        $this->workOrderNoteService->insertWorkOrderNote($workOrder->id, [
                'workorder_status_id' => $newStatus,
                'notes' => $notes,
                'user_id' => \Auth::user()->userId(),
                'public' => false,
                'auto' => true
            ]);
    }
}