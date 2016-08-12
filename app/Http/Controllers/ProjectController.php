<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Project\ProjectService;

class ProjectController extends ApiController
{
    /**
     * @var ProjectService
     */
    private $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function getIndex()
    {
        $completed = $this->input('completed') === 'true';
        if (\Auth::user()->isAdmin()) {
            return $this->data($this->service->allProjects($completed))->respond();
        } else {
            return $this->data($this->service->userProjects(\Auth::user()->userId(), $completed))->respond();
        }
    }

    public function postIndex()
    {
        $project = $this->service->createProject(['name' => $this->input('name')], $this->input('users'), $this->input('template'));
        return $this->data($project->toArray())->respond();
    }

    public function getProject($id)
    {
        return $this->data($this->service->getProject($id)->toArray())->respond();
    }

    public function putProject($id)
    {
        if ($this->input('complete')) {
            $this->service->completeProject($id);
        }
    }

    public function postTask()
    {
        $this->service->createTask(['name' => $this->input('name'), 'project_id' => $this->input('projectId'), 'parent' => $this->input('parent'), 'description' => $this->input('description')]);
    }

    public function postComment()
    {
        return $this->data($this->service->createComment(['content' => $this->input('content'), 'task_id' => $this->input('task_id'), 'user_id' => \Auth::user()->userId()])->toArray())->respond();
    }

    public function putTask($id)
    {
        if ($this->input('complete')) {
            $this->service->completeTask($id);
        }
    }

    public function postTemplate()
    {
        $this->service->createTemplate($this->input('projectId'), $this->input('name'));
    }

    public function getTemplates()
    {
        return $this->data($this->service->getTemplates()->toArray())->respond();
    }
}