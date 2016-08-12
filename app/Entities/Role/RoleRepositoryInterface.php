<?php namespace Biffy\Entities\Role;

use Biffy\Entities\AbstractRepositoryInterface;

interface RoleRepositoryInterface extends AbstractRepositoryInterface
{
    public function edit($roleId, $name);

    /**
     * @param int $roleId
     * @return array
     */
    public function getPermissionIds($roleId);

    /**
     * Removes existing permissions and sets the role's permissions to those specified
     * @param $roleId
     * @param $permissions
     */
    public function setPermissions($roleId, $permissions);
} 