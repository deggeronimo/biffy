<?php namespace Biffy\Services\Entities\MarketingRun;

use Biffy\Entities\MarketingRun\MarketingRunRepositoryInterface;
use Biffy\Services\Entities\Service;

class MarketingRunService extends Service
{
    public function __construct(MarketingRunRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}