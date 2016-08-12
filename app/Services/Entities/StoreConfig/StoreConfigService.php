<?php namespace Biffy\Services\Entities\StoreConfig;

use Biffy\Entities\StoreConfig\StoreConfigRepositoryInterface;
use Biffy\Events\CacheNeeded;
use Biffy\Services\Entities\Service;

/**
 * Class StoreConfigService
 * @package Biffy\Services\Entities\StoreConfig
 */
class StoreConfigService extends Service
{
    /**
     * @param StoreConfigRepositoryInterface $repo
     */
    public function __construct(StoreConfigRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getEntries($ignoreCache = false, $storeId = null)
    {
        $storeId = is_null($storeId) ? \Auth::user()->storeId() : $storeId;
        if (!$ignoreCache && \Cache::storeId($storeId)->has('store-config')) {
            $cached = \Cache::storeId($storeId)->get('store-config');
            $cached = array_map(function ($key, $val) {
                    return [
                        'id' => $val['id'],
                        'config_id' => $key,
                        'value' => $val['v']
                    ];
                }, array_keys($cached), array_values($cached));
            return $cached;
        }

        if (!$ignoreCache) {
            $this->needsCache($storeId);
        }

        return $this->repo->getEntries($storeId);
    }

    public function process($input)
    {
        $data = $this->simpleMap($input);
        $entries = $this->simpleMap($this->getEntries(true)->toArray());
        $update = [];

        foreach ($data as $id => $value) {
            if ($value != $entries[$id]) {
                $update[$id] = $value;
            }
        }

        foreach ($update as $id => $value) {
            $this->update($id, ['value' => $value]);
        }

        $toCache = $this->cacheMap($input);

        \Cache::storeId(\Auth::user()->storeId())->put('store-config', $toCache);
    }

    public function simpleMap($entries)
    {
        return array_combine(
            array_map(function ($val) {
                    return $val['id'];
                }, $entries),
            array_map(function ($val) {
                    return $val['value'];
                }, $entries));
    }

    public function handleNew($data)
    {
        foreach ($data as $d) {
            $this->create($d);
            $this->needsCache($d['store_id']);
        }
    }

    public function cacheData($storeId)
    {
        $entries = $this->getEntries(true, $storeId)->toArray();
        \Cache::storeId($storeId)->put('store-config', $this->cacheMap($entries));
    }

    public function needsCache($storeId)
    {
        \Cache::storeId($storeId)->forget('store-config');
        event(new CacheNeeded(['type' => 'StoreConfig', 'storeId' => $storeId]));
    }

    protected function cacheMap($data)
    {
        return array_combine(
            array_map(function ($val) {
                    return $val['config_id'];
                }, $data),
            array_map(function ($val) {
                    return [
                        'v' => $val['value'],
                        'id' => $val['id']
                    ];
                }, $data));
    }
}