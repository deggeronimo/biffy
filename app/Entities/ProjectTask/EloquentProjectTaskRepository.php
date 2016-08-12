<?php namespace Biffy\Entities\ProjectTask;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentProjectTaskRepository extends EloquentAbstractRepository implements ProjectTaskRepositoryInterface
{
    public function __construct(ProjectTask $model)
    {
        $this->model = $model;
    }

    public function completeTask($id)
    {
        /** @var ProjectTask $task */
        $task = $this->find($id);
        $task->completeTask();
    }
}