<?php namespace Biffy\Services\Entities\ChecklistFunction;

use Biffy\Entities\ChecklistFunction\ChecklistFunctionRepositoryInterface;
use Biffy\Services\Entities\Service;

class ChecklistFunctionService extends Service
{
    public function __construct(ChecklistFunctionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}