<?php namespace Biffy\Entities\Permission;

use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Exceptions\ValidationFailedException;

/**
 * Class EloquentPermissionRepository
 * @package Biffy\Entities\Permission
 */
class EloquentPermissionRepository extends EloquentAbstractRepository implements PermissionRepositoryInterface
{
    /**
     * @param Permission $model
     */
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * @param $id
     * @param $name
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * @throws ValidationFailedException
     */
    public function edit($id, $name)
    {
        // todo actual validation
        if (!$name) {
            throw new ValidationFailedException;
        }

        $permission = $this->find($id);
        $permission->name = $name;
        $permission->save();
        return $permission;
    }
}