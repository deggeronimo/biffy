<?php

namespace Biffy\Services\Config;

use Biffy\Entities\Config\ConfigRepositoryInterface;
use Biffy\Entities\StoreConfig\StoreConfigRepositoryInterface;

class StoreConfigService
{
    /**
     * @var \Biffy\Entities\Config\ConfigRepositoryInterface
     */
    private $config;

    /**
     * @var \Biffy\Entities\StoreConfig\StoreConfigRepositoryInterface
     */
    private $storeConfig;

    private $configKeys = [];

    public function __construct(ConfigRepositoryInterface $config, StoreConfigRepositoryInterface $storeConfig)
    {
        $this->config = $config;
        $this->storeConfig = $storeConfig;

        $this->configKeys = $this->config->listNames();
    }

    public function get($key, $default = null)
    {
        $configId = array_search($key, $this->configKeys);

        if (!$configId) {
            return $default;
        }

        $storeId = \Auth::user()->storeId();

        $entry = null;
        if (\Cache::storeId($storeId)->has('store-config')) {
            $storeConfig = \Cache::storeId($storeId)->get('store-config');

            if (array_key_exists($configId, $storeConfig)) {
                $entry = ['value' => $storeConfig[$configId]['v']];
            }
        } else {
            $entry = $this->storeConfig->getEntry($storeId, $configId);
            // todo fire cache event
        }

        if (is_null($entry)) {
            return $default;
        }

        return $entry['value'];
    }
} 