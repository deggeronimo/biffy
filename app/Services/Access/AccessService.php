<?php

namespace Biffy\Services\Access;

use Biffy\Exceptions\PermissionNotFoundException;
use Biffy\Exceptions\UserMissingPermissionException;
use Biffy\Services\Entities\Permission\PermissionService;

class AccessService
{
    /**
     * @var array
     */
    protected $permissions = [];

    public function __construct(PermissionService $permissionService)
    {
        $permissions = $permissionService->all();
        $this->permissions = array_map(function ($val) {
                return $val['name'];
            }, $permissions);
    }

    /**
     * @param string $permissionStr
     * @return bool
     */
    public function permissionExists($permissionStr)
    {
        if (in_array($permissionStr, $this->permissions)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $permissionStr
     * @param bool $throw
     * @throws \Biffy\Exceptions\UserMissingPermissionException
     * @throws \Biffy\Exceptions\PermissionNotFoundException
     * @return bool
     */
    public function check($permissionStr, $throw = true)
    {
        if (\Auth::user()->isAdmin()) {
            return true;
        }

        if (!$this->permissionExists($permissionStr)) {
            throw new PermissionNotFoundException;
        }

        if (!\Auth::hasPermission($permissionStr)) {
            if ($throw) {
                throw new UserMissingPermissionException;
            } else {
                return false;
            }
        }

        return true;
    }
} 