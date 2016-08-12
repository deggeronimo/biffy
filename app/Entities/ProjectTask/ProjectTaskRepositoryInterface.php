<?php namespace Biffy\Entities\ProjectTask;

use Biffy\Entities\AbstractRepositoryInterface;

interface ProjectTaskRepositoryInterface extends AbstractRepositoryInterface
{
    public function completeTask($id);
}