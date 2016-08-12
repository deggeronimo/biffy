<?php namespace Biffy\Entities\Setting;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentSettingRepository extends EloquentAbstractRepository implements SettingRepositoryInterface
{
    public function __construct(Setting $model)
    {
        $this->model = $model;
    }
}