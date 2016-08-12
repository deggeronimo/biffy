<?php namespace Biffy\Entities\ProjectTemplate;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentProjectTemplateRepository extends EloquentAbstractRepository implements ProjectTemplateRepositoryInterface
{
    public function __construct(ProjectTemplate $model)
    {
        $this->model = $model;
    }
}