<?php namespace Biffy\Entities\WorkOrderNote;

use Biffy\Entities\AbstractRepositoryInterface;

interface WorkOrderNoteRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param int $workOrderId
     * @return mixed
     */
    public function getWorkOrderNoteList($workOrderId);
}