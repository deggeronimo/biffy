<?php namespace Biffy\Entities\UserSetting;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentUserSettingRepository extends EloquentAbstractRepository implements UserSettingRepositoryInterface
{
    public function __construct(UserSetting $model)
    {
        $this->model = $model;
    }
}