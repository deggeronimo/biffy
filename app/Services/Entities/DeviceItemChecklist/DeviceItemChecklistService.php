<?php namespace Biffy\Services\Entities\DeviceItemChecklist;

use Biffy\Entities\DeviceItemChecklist\DeviceItemChecklistRepositoryInterface;
use Biffy\Services\Entities\Service;
use Illuminate\Pagination\LengthAwarePaginator;

class DeviceItemChecklistService extends Service
{
    public function __construct(DeviceItemChecklistRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param int $count
     * @param int $page
     * @param mixed $filter
     * @param mixed $sorting
     *
     * @returns LengthAwarePaginator
     */
    public function getIndex($count, $page, $filter, $sorting)
    {
        return $this->inStore()->repo->paginate($count, $page)->filterBy($filter)->sortBy($sorting)->get();
    }
}