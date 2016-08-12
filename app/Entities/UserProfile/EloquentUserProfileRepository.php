<?php namespace Biffy\Entities\UserProfile;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentUserProfileRepository extends EloquentAbstractRepository implements UserProfileRepositoryInterface
{
    public function __construct(UserProfile $model)
    {
        $this->model = $model;
    }
}