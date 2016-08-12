<?php namespace Biffy\Entities\Language;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentLanguageRepository extends EloquentAbstractRepository implements LanguageRepositoryInterface
{
    public function __construct(Language $model)
    {
        $this->model = $model;
    }
}