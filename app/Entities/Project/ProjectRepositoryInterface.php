<?php namespace Biffy\Entities\Project;

use Biffy\Entities\AbstractRepositoryInterface;

interface ProjectRepositoryInterface extends AbstractRepositoryInterface
{
    public function allForUser($userId, $completed);
    public function completeProject($id);
    public function allProjects($completed);
}