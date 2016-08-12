<?php namespace Biffy\Services\Cache;

use Illuminate\Cache\CacheManager;

class CacheService
{
    private $cache;

    private $userId;
    private $storeId;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cache = $cacheManager;
    }

    public function userId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function storeId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    public function put($key, $value, $duration = null)
    {
        $key = $this->apply($key);

        $this->store()->put($key, $value, $this->duration($duration));

        $this->clear();
    }

    public function has($key)
    {
        $key = $this->apply($key);
        $this->clear();
        return $this->store()->has($key);
    }

    public function get($key, $default = null)
    {
        $key = $this->apply($key);
        $this->clear();
        return $this->store()->get($key, $default);
    }

    public function forget($key)
    {
        $key = $this->apply($key);
        $this->clear();
        $this->store()->forget($key);
    }

    public function duration($duration)
    {
        switch ($duration) {
            default:
            case 'long':
                return 60;
        }
    }

    /**
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function store()
    {
        return $this->cache->store();
    }

    public function apply($key)
    {
        if (!is_null($this->userId)) {
            $key = 'u' . $this->userId . '.' . $key;
        }
        if (!is_null($this->storeId)) {
            $key = 's' . $this->storeId . '.' . $key;
        }
        return $key;
    }

    public function clear()
    {
        $this->userId = null;
        $this->storeId = null;
    }
} 