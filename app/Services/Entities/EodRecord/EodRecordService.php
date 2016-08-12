<?php namespace Biffy\Services\Entities\EodRecord;

use Biffy\Entities\EodRecord\EodRecordRepositoryInterface;
use Biffy\Services\Entities\Service;

class EodRecordService extends Service
{
    public function __construct(EodRecordRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}