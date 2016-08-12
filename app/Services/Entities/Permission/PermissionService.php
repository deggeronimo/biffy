<?php namespace Biffy\Services\Entities\Permission;

use Biffy\Entities\Permission\PermissionRepositoryInterface;
use Biffy\Events\CacheNeeded;
use Biffy\Services\Entities\Service;

/**
 * Class PermissionService
 * @package Biffy\Services\Entities\Permission
 */
class PermissionService extends Service
{
    /**
     * @param PermissionRepositoryInterface $repo
     */
    public function __construct(PermissionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getStorePermissions()
    {
        return \Cache::get('store-permissions', function () {
                $this->needsCache();
                return $this->repo->findAllBy('global', 0);
            });
    }

    public function getGlobalPermissions()
    {
        return \Cache::get('global-permissions', function () {
                $this->needsCache();
                return $this->repo->findAllBy('global', 1);
            });
    }

    public function all($ignoreCache = false)
    {
        $closure = function () use ($ignoreCache) {
            if (!$ignoreCache) {
                $this->needsCache();
            }
            return $this->repo->all()->toArray();
        };

        if (!$ignoreCache) {
            return \Cache::get('all-permissions', $closure);
        }

        return $closure();
    }

    public function needsCache()
    {
        \Cache::forget('all-permissions');
        \Cache::forget('global-permissions');
        \Cache::forget('store-permissions');
        event(new CacheNeeded(['type' => 'Permission']));
    }

    public function cacheData()
    {
        $data = $this->all(true);
        $store = [];
        $global = [];
        $all = [];

        foreach ($data as $d) {
            $arr = [
                'id' => $d['id'],
                'name' => $d['name'],
                'description' => $d['description'],
                'global' => $d['global']
            ];

            $all[] = $arr;
            if ($d['global']) {
                $global[] = $arr;
            } else {
                $store[] = $arr;
            }
        }

        \Cache::put('all-permissions', $all);
        \Cache::put('global-permissions', $global);
        \Cache::put('store-permissions', $store);
    }
}