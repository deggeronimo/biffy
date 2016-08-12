<?php namespace Biffy\Services\Entities\FeedbackCallLog;

use Biffy\Entities\FeedbackCallLog\FeedbackCallLogRepositoryInterface;
use Biffy\Services\Entities\Service;

class FeedbackCallLogService extends Service
{
    public function __construct(FeedbackCallLogRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}