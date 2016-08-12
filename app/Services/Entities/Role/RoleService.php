<?php namespace Biffy\Services\Entities\Role;

use Biffy\Entities\Role\RoleRepositoryInterface;
use Biffy\Services\Entities\Service;

/**
 * Class RoleService
 * @package Biffy\Services\Entities\Role
 */
class RoleService extends Service
{
    /**
     * @param RoleRepositoryInterface $repo
     */
    public function __construct(RoleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param int $roleId
     * @return array
     */
    public function getPermissionIds($roleId)
    {
        return $this->repo->getPermissionIds($roleId);
    }

    /**
     * Removes existing permissions and sets the role's permissions to those specified
     * @param $roleId
     * @param $permissions
     */
    public function setPermissions($roleId, $permissions)
    {
        return $this->repo->setPermissions($roleId, $permissions);
    }
}