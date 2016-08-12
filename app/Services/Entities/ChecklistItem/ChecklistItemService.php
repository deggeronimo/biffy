<?php namespace Biffy\Services\Entities\ChecklistItem;

use Biffy\Entities\ChecklistItem\ChecklistItemRepositoryInterface;
use Biffy\Services\Entities\Service;

class ChecklistItemService extends Service
{
    public function __construct(ChecklistItemRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}