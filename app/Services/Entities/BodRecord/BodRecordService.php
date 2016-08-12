<?php namespace Biffy\Services\Entities\BodRecord;

use Biffy\Entities\BodRecord\BodRecordRepositoryInterface;
use Biffy\Services\Entities\Service;

class BodRecordService extends Service
{
    public function __construct(BodRecordRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}