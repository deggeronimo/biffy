<?php namespace Biffy\Services\Entities\Project;

use Biffy\Entities\Project\ProjectRepositoryInterface;
use Biffy\Entities\ProjectTask\ProjectTaskRepositoryInterface;
use Biffy\Entities\ProjectTaskComment\ProjectTaskCommentRepositoryInterface;
use Biffy\Entities\ProjectTemplate\ProjectTemplateRepositoryInterface;
use Biffy\Services\Entities\Service;

class ProjectService extends Service
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $projectRepo;

    /**
     * @var ProjectTaskRepositoryInterface
     */
    protected $taskRepo;

    /**
     * @var ProjectTaskCommentRepositoryInterface
     */
    protected $commentRepo;

    /**
     * @var ProjectTemplateRepositoryInterface
     */
    protected $templateRepo;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface, ProjectTaskRepositoryInterface $projectTaskRepositoryInterface,
        ProjectTaskCommentRepositoryInterface $projectTaskCommentRepositoryInterface, ProjectTemplateRepositoryInterface $projectTemplateRepositoryInterface)
    {
        $this->projectRepo = $projectRepositoryInterface;
        $this->taskRepo = $projectTaskRepositoryInterface;
        $this->commentRepo = $projectTaskCommentRepositoryInterface;
        $this->templateRepo = $projectTemplateRepositoryInterface;
    }

    public function createProject($attributes, $users, $templateId)
    {
        $project = $this->projectRepo->create($attributes);

        if (!is_null($templateId)) {
            $template = $this->templateRepo->find($templateId);
            $data = json_decode($template->data, true);

            foreach ($data as $task) {
                $taskModel = $this->taskRepo->create(['name' => $task['name'], 'project_id' => $project->id, 'parent' => null]);

                foreach ($task['subtasks'] as $subtask) {
                    $this->taskRepo->create(['name' => $subtask['n'], 'description' => $subtask['d'], 'project_id' => null, 'parent' => $taskModel->id]);
                }
            }
        }

        $userIds = array_map(function ($val) {
                return $val['id'];
            }, $users);

        $project->users()->attach($userIds);

        return $project;
    }

    public function createTask($attributes)
    {
        return $this->taskRepo->create($attributes);
    }

    public function allProjects($completed = false)
    {
        return $this->projectRepo->allProjects($completed);
    }

    public function userProjects($userId, $completed = false)
    {
        return $this->projectRepo->allForUser($userId, $completed);
    }

    public function getProject($id)
    {
        return $this->projectRepo->with(['tasks.subtasks.comments.user', 'users'])->find($id);
    }

    public function createComment($attributes)
    {
        return $this->commentRepo->create($attributes);
    }

    public function completeTask($id)
    {
        $this->taskRepo->completeTask($id);
    }

    public function createTemplate($projectId, $templateName)
    {
        $project = $this->getProject($projectId)->toArray();

        $tasks = array_map(function ($val) {
                return [
                    'name' => $val['name'],
                    'subtasks' => array_map(function ($val) {
                            return ['n' => $val['name'], 'd' => $val['description']];
                        }, $val['subtasks'])
                ];
            }, $project['tasks']);

        $this->templateRepo->create(['name' => $templateName, 'data' => json_encode($tasks)]);
    }

    public function getTemplates()
    {
        return $this->templateRepo->all();
    }

    public function completeProject($id)
    {
        $this->projectRepo->completeProject($id);
    }
} 