<?php namespace Biffy\Entities\WorkOrder;

use Biffy\Entities\AbstractRepositoryInterface;

/**
 * Interface WorkOrderRepositoryInterface
 * @package Biffy\Entities\WorkOrder
 */
interface WorkOrderRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $storeId
     * @param $count
     * @param $workorderStatus
     * @return mixed
     */
    public function getPaginatedWorkOrderIndexForStoreWithStatus($storeId, $count, $workorderStatus);

    /**
     * @param $storeId
     * @param $count
     * @param $workorderStatus
     * @param $filterBy
     * @return mixed
     */
    public function getFilteredPaginatedWorkOrderIndexForStoreWithStatus($storeId, $count, $workorderStatus, $filterBy);

    /**
     * @param int $storeId
     * @param $count
     * @return mixed
     */
    public function getPaginatedWorkOrderIndexForStore($storeId, $count);
}