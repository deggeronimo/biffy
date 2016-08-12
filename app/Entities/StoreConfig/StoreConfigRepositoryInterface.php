<?php namespace Biffy\Entities\StoreConfig;

use Biffy\Entities\AbstractRepositoryInterface;

interface StoreConfigRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param int $storeId
     * @param int $configId
     * @return mixed
     */
    public function getEntry($storeId, $configId);

    public function getEntries($storeId);
} 