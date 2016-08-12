<?php namespace Biffy\Services\Entities\Feedback;

use Biffy\Entities\Feedback\FeedbackRepositoryInterface;
use Biffy\Services\Entities\Service;

class FeedbackService extends Service
{
    public function __construct(FeedbackRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}