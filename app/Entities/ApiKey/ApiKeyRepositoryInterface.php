<?php namespace Biffy\Entities\ApiKey;

use Biffy\Entities\AbstractRepositoryInterface;

interface ApiKeyRepositoryInterface extends AbstractRepositoryInterface
{
    public function countByKey($key);
}