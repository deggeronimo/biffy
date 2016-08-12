<?php namespace Biffy\Entities\ChecklistItem;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentChecklistItemRepository extends EloquentAbstractRepository implements ChecklistItemRepositoryInterface
{
    public function __construct(ChecklistItem $model)
    {
        $this->model = $model;
    }
}