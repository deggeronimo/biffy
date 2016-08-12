<?php namespace Biffy\Services\Entities\MarketingLocation;

use Biffy\Entities\MarketingLocation\MarketingLocationRepositoryInterface;
use Biffy\Services\Entities\Service;

class MarketingLocationService extends Service
{
    public function __construct(MarketingLocationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}