<?php namespace Biffy\Entities\PermissionUser;

use Biffy\Entities\AbstractRepositoryInterface;

interface PermissionUserRepositoryInterface extends AbstractRepositoryInterface
{
    public function getGlobalPermissions($userId);

    public function getStorePermissions($userId, $storeId);

    /**
     * @param int $userId
     * @param array $permissionIds
     * @return mixed
     */
    public function setGlobalPermissions($userId, $permissionIds);

    /**
     * @param int $userId
     * @param int $storeId
     * @param array $permissionIds
     * @return mixed
     */
    public function setStorePermissions($userId, $storeId, $permissionIds);
} 