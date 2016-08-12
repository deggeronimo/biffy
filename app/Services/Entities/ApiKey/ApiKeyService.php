<?php namespace Biffy\Services\Entities\ApiKey;

use Biffy\Entities\ApiKey\ApiKeyRepositoryInterface;
use Biffy\Services\Entities\Service;

class ApiKeyService extends Service
{
    /**
     * @var ApiKeyRepositoryInterface
     */
    protected $repo;

    public function __construct(ApiKeyRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function keyExists($key)
    {
        $count = $this->repo->countByKey($key);
        return $count > 0;
    }
} 