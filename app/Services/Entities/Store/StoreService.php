<?php namespace Biffy\Services\Entities\Store;

use Biffy\Entities\Store\StoreRepositoryInterface;
use Biffy\Events\CacheNeeded;
use Biffy\Services\Entities\Service;

/**
 * Class StoreService
 * @package Biffy\Services\Entities\Store
 */
class StoreService extends Service
{
    /**
     * @param StoreRepositoryInterface $repo
     */
    public function __construct(StoreRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAllIds()
    {
        return $this->repo->getAllIds();
    }

    public function getUsers($storeId)
    {
        return $this->repo->find($storeId)->users();
    }

    public function createFromGoogle($group, $groupId)
    {
        $this->create([
                'name' => str_replace('Group ', '', $group->name),
                'group_id' => $groupId
            ]);
    }

    public function getWithConfig($storeId)
    {
        if (\Cache::has('config') && \Cache::storeId($storeId)->has('store') && \Cache::storeId($storeId)->has('store-config')) {
            $config = \Cache::get('config');
            $store = \Cache::storeId($storeId)->get('store');
            $storeConfig = \Cache::storeId($storeId)->get('store-config');

            foreach ($config as $v) {
                $storeConfigEntry = $storeConfig[$v['id']];
                $store['config'][] = [
                    'id' => $storeConfigEntry['id'],
                    'config_id' => $v['id'],
                    'key' => $v['key'],
                    'value' => $storeConfigEntry['v']
                ];
            }

            return $store;
        }

        if (!\Cache::storeId($storeId)->has('store-config')) {
            event(new CacheNeeded(['type' => 'StoreConfig', 'storeId' => $storeId]));
        }
        if (!\Cache::has('config')) {
            event(new CacheNeeded(['type' => 'Config']));
        }

        $store = $this->repo->with(['config' => function ($query) {
                $query->with(['config' => function ($query) {
                        $query->select('id', 'key');
                    }]);
            }])->find($storeId);

        foreach ($store['config'] as $k => $v) {
            $store['config'][$k]['key'] = $v['config']['key'];
            unset($store['config'][$k]['config']);
        }

        $toCache = $store->toArray();
        unset($toCache['config']);
        \Cache::storeId($storeId)->put('store', $toCache);

        return $store;
    }
}