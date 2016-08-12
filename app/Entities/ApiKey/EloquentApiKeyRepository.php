<?php namespace Biffy\Entities\ApiKey;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentApiKeyRepository extends EloquentAbstractRepository implements ApiKeyRepositoryInterface
{
    public function __construct(ApiKey $model)
    {
        $this->model = $model;
    }

    public function countByKey($key)
    {
        return $this->make()->where('key', '=', $key)->limit(1)->count();
    }
}