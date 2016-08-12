<?php namespace Biffy\Entities\Role;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentRoleRepository
 * @package Biffy\Entities\Role
 */
class EloquentRoleRepository extends EloquentAbstractRepository implements RoleRepositoryInterface
{
    /**
     * @param Role $model
     */
    public function __construct(Role $model)
    {
        $this->model = $model;

        $this->with(['permissions']);
    }

    /**
     * @param $roleId
     * @param $name
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function edit($roleId, $name)
    {
        $role = $this->find($roleId);
        $role->name = $name;
        $role->save();

        return $role;
    }

    /**
     * Removes existing permissions and sets the role's permissions to those specified
     * @param $roleId
     * @param $permissions
     */
    public function setPermissions($roleId, $permissions)
    {
        $this->find($roleId)->permissions()->sync($permissions);
    }

    /**
     * @param int $roleId
     * @return array
     */
    public function getPermissionIds($roleId)
    {
        return $this->find($roleId)->permissions->modelKeys();
    }
}