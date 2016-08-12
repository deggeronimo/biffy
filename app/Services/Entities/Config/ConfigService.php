<?php namespace Biffy\Services\Entities\Config;

use Biffy\Entities\Config\ConfigRepositoryInterface;
use Biffy\Events\CacheNeeded;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\Store\StoreService;
use Biffy\Services\Entities\StoreConfig\StoreConfigService;

/**
 * Class ConfigService
 * @package Biffy\Services\Entities\Config
 */
class ConfigService extends Service
{
    /**
     * @param ConfigRepositoryInterface $repo
     */
    public function __construct(ConfigRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create($attributes)
    {
        $config = parent::create($attributes);
        $this->handleNew($config->id, $config->default);
        return $config;
    }

    public function handleNew($configId, $default)
    {
        $this->needsCache();
        /** @var StoreService $storeService */
        $storeService = app('Biffy\Services\Entities\Store\StoreService');
        $storeIds = $storeService->getAllIds();

        $data = array_map(function ($val) use ($configId, $default) {
                return ['config_id' => $configId, 'store_id' => $val, 'value' => $default];
            }, $storeIds);

        /** @var StoreConfigService $storeConfigService */
        $storeConfigService = app('Biffy\Services\Entities\StoreConfig\StoreConfigService');
        $storeConfigService->handleNew($data);
    }

    public function handleDeleted()
    {
        $this->needsCache();

        /** @var StoreService $storeService */
        $storeService = app('Biffy\Services\Entities\Store\StoreService');
        $storeIds = $storeService->getAllIds();

        /** @var StoreConfigService $storeConfigService */
        $storeConfigService = app('Biffy\Services\Entities\StoreConfig\StoreConfigService');
        foreach ($storeIds as $id) {
            $storeConfigService->needsCache($id);
        }
    }

    public function all()
    {
        return \Cache::get('config', function () {
                $this->needsCache();
                return $this->repo->all();
            });
    }

    public function cacheData()
    {
        \Cache::put('config', $this->repo->dataForCaching()->toArray());
    }

    public function needsCache()
    {
        \Cache::forget('config');
        event(new CacheNeeded(['type' => 'Config']));
    }
}