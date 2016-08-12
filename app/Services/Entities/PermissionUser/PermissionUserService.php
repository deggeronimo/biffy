<?php namespace Biffy\Services\Entities\PermissionUser;

use Biffy\Entities\PermissionUser\PermissionUserRepositoryInterface;
use Biffy\Events\CacheNeeded;
use Biffy\Services\Entities\Service;

class PermissionUserService extends Service
{
    public function __construct(PermissionUserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param int $userId
     * @param bool $ignoreCache
     * @return mixed
     */
    public function getGlobalPermissions($userId, $ignoreCache = false)
    {
        if (!$ignoreCache && \Cache::userId($userId)->has('global-permissions')) {
            return \Cache::userId($userId)->get('global-permissions');
        }

        $permissions = $this->repo->getGlobalPermissions($userId);

        \Cache::userId($userId)->put('global-permissions', $permissions);

        return $permissions;
    }

    public function getStorePermissions($userId, $storeId, $ignoreCache = false)
    {
        if (!$ignoreCache && \Cache::storeId($storeId)->userId($userId)->has('store-permissions')) {
            return \Cache::storeId($storeId)->userId($userId)->get('store-permissions');
        }

        $permissions = $this->repo->getStorePermissions($userId, $storeId);

        \Cache::storeId($storeId)->userId($userId)->put('store-permissions', $permissions);

        return $permissions;
    }

    /**
     * @param int $userId
     * @param array $permissionIds
     * @return void
     */
    public function setGlobalPermissions($userId, $permissionIds)
    {
        $this->repo->setGlobalPermissions($userId, $permissionIds);
        $this->needsCache($userId);
    }

    /**
     * @param $userId
     * @param $storeId
     * @param $permissionIds
     * @return void
     */
    public function setStorePermissions($userId, $storeId, $permissionIds)
    {
        $this->repo->setStorePermissions($userId, $storeId, $permissionIds);
        $this->needsCache($userId, $storeId);
    }

    public function needsCache($userId, $storeId = null)
    {
        if (is_null($storeId)) {
            \Cache::userId($userId)->forget('global-permissions');
        } else {
            \Cache::storeId($storeId)->userId($userId)->forget('store-permissions');
        }
        event(new CacheNeeded(['type' => 'PermissionUser', 'storeId' => $storeId, 'userId' => $userId]));
    }

    public function cacheData($storeId, $userId)
    {
        if (is_null($storeId)) {
            $this->getGlobalPermissions($userId, true);
        } else {
            $this->getStorePermissions($userId, $storeId, true);
        }
    }
} 