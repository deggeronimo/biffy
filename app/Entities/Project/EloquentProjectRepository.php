<?php namespace Biffy\Entities\Project;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentProjectRepository extends EloquentAbstractRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function allForUser($userId, $completed)
    {
        $query = $this->make();
        $query = $completed ? $query->completed() : $query->active();
        return $query->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', '=', $userId);
            })->get();
    }

    public function allProjects($completed)
    {
        $query = $this->make();
        $query = $completed ? $query->completed() : $query->active();
        return $query->get();
    }

    public function completeProject($id)
    {
        /** @var Project $project */
        $project = $this->find($id);
        $project->completeProject();
    }
}