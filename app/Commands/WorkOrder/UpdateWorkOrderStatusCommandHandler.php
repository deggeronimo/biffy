<?php namespace Biffy\Commands\WorkOrder;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Services\Entities\CommandFailedException;
use Biffy\Services\Entities\RollbackFailedException;
use Biffy\Services\Entities\WorkOrder\WorkOrderService;
use Exception;

/**
 * Class UpdateWorkOrderStatusCommandHandler
 * @package Biffy\Commands\WorkOrder
 */
class UpdateWorkOrderStatusCommandHandler implements CommandHandler
{
    /**
     * @var WorkOrderService
     */
    private $workOrderService;

    /**
     * @param WorkOrderService $workOrderService
     */
    public function __construct(WorkOrderService $workOrderService)
    {
        $this->workOrderService = $workOrderService;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws CommandFailedException
     */
    public function handle(AbstractCommand $command)
    {
        $workOrderItem = $this->workOrderService->find($command->work_order_id);
        $command->old_status_id = $workOrderItem->workorder_status_id;
        $workOrderItem->workorder_status_id = $command->workorder_status_id;
        $workOrderItem->next_update = $command->next_update;
        $workOrderItem->save();
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command)
    {
        try
        {
            $workOrderItem = $this->workOrderService->find($command->work_order_id);
            $workOrderItem->workorder_status_id = $command->old_status_id;
            $workOrderItem->save();
        }
        catch (Exception $e)
        {
            throw new RollbackFailedException($e->getMessage(), 0, $e);
        }
    }
}