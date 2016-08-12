<?php namespace Biffy\Services\Entities\FeedbackDoc;

use Biffy\Entities\FeedbackDoc\FeedbackDocRepositoryInterface;
use Biffy\Services\Entities\Service;

class FeedbackDocService extends Service
{
    public function __construct(FeedbackDocRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getUnassignedFeedbackDocs()
    {
        return $this->repo->getUnassignedFeedbackDocs()->toArray();
    }
}