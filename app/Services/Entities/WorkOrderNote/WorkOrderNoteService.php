<?php namespace Biffy\Services\Entities\WorkOrderNote;

use Auth;
use Biffy\Entities\WorkOrder\WorkOrderRepositoryInterface;
use Biffy\Entities\WorkOrderNote\WorkOrderNoteRepositoryInterface;
use Biffy\Entities\WorkOrderStatus\WorkOrderStatusRepositoryInterface;
use Biffy\Services\Entities\Service;

/**
 * Class WorkOrderService
 * @package Biffy\Services\Entities\WorkOrderNote
 */
class WorkOrderNoteService extends Service
{
    protected $workOrderRepo;
    protected $workOrderStatusRepo;

    /**
     * @param WorkOrderNoteRepositoryInterface $repo
     * @param WorkOrderRepositoryInterface $workOrderRepo
     * @param WorkOrderStatusRepositoryInterface $workOrderStatusRepo
     */
    public function __construct(WorkOrderNoteRepositoryInterface $repo, WorkOrderRepositoryInterface $workOrderRepo,
                                WorkOrderStatusRepositoryInterface $workOrderStatusRepo)
    {
        $this->repo = $repo;

        $this->workOrderRepo = $workOrderRepo;
        $this->workOrderStatusRepo = $workOrderStatusRepo;
    }

    /**
     * @param int $workOrderId
     * @return array
     */
    public function getWorkOrderNoteList($workOrderId)
    {
        return $this->repo->getWorkOrderNoteList($workOrderId)->toArray();
    }

    /**
     * @param $workOrderId
     * @param $data
     * @return array
     */
    public function insertWorkOrderNote($workOrderId, $data)
    {
        $workOrder = $this->workOrderRepo->find($workOrderId);

        $workOrderStatusId = $data['workorder_status_id'];
        $workOrderStatus = $this->workOrderStatusRepo->find($workOrderStatusId);

        if (!isset($data['next_update_time']))
        {
            if ($workOrderStatus->next_time == 1)
            {
                $nextUpdateTime = $workOrder->next_update;
            }
            else
            {
                $nextUpdateTime = date ("Y-m-d H:i:s", time() + $workOrderStatus->next_time);
            }
        }
        else
        {
            $nextUpdateTime = date ("Y-m-d H:i:s", $data['next_update_time']);
        }

        $workOrderNote = $this->repo->create([
            'work_order_id' => $workOrderId,
            'workorder_status_id' => $workOrderStatusId,
            'next_update_time' => $nextUpdateTime,
            'notes' => $data['notes'],
            'user_id' => Auth::user()->userId(true),
            'public' => $data['public'],
            'auto' => $data['auto'],
            'diag' => isset($data['diag']) && $data['diag'] !== 'null' ? $data['diag'] : null
        ]);

        $workOrder->workorder_status_id = $workOrderStatusId;
        $workOrder->next_update = $nextUpdateTime;
        $workOrder->save();

        return $workOrderNote;
    }

//    public function insertWorkOrderNote($workOrderId, $data, $actionId = null)
//    {
//        $workOrderNote = $this->repo->create([
//            'work_order_id' => $workOrderId,
//            'workorder_status_id' => $data['workorder_status_id'],
//            'next_update_time' => $data['next_update_time'],
//            'notes' => $data['notes'],
//            'user_id' => $data['user_id'],
//            'action_id' => $actionId,
//            'public' => $data['public'],
//            'auto' => $data['auto'],
//            'diag' => isset($data['diag']) && $data['diag'] !== 'null' ? $data['diag'] : null
//        ]);
//        $workOrder = $workOrderNote->workOrder;
//        $workOrder->workorder_status_id = $data['workorder_status_id'];
//        $workOrder->next_update = $data['next_update_time'];
//        $workOrder->save();
//        return $workOrderNote;
//    }
}