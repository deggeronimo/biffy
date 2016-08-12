<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\PermissionUser\PermissionUserService;
use Biffy\Services\Entities\Role\RoleService;

/**
 * Class RoleUserController
 * @package Biffy\Http\Controllers
 */
class RoleUserController extends ApiController
{
    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * @var PermissionUserService
     */
    protected $permissionUserService;

    /**
     * @param RoleService $roleService
     * @param PermissionUserService $permissionUserService
     */
    public function __construct(RoleService $roleService, PermissionUserService $permissionUserService)
    {
        $this->roleService = $roleService;
        $this->permissionUserService = $permissionUserService;
    }

    /**
     * @param $userId
     * @param $roleId
     */
    public function update($userId, $roleId)
    {
        $permissionIds = $this->roleService->getPermissionIds($roleId);
        $this->permissionUserService->setStorePermissions($userId, \Auth::user()->storeId(), $permissionIds);
    }
}