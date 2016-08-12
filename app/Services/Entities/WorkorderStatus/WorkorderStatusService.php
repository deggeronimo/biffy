<?php namespace Biffy\Services\Entities\WorkorderStatus; 

use Biffy\Entities\WorkOrderStatus\WorkOrderStatusRepositoryInterface;
use Biffy\Services\Entities\Service;

class WorkorderStatusService extends Service
{
    public function __construct(WorkOrderStatusRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getList($filter, $sorting)
    {
        $data = parent::getList($filter, $sorting);
        $data = array_map(function ($val) {
                $val['action_text'] = \LanguageTranslator::get('workorder.status.action.' . $val['action_text_key']);
                return $val;
            }, $data->toArray());
        return $data;
    }
}