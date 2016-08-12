<?php namespace Biffy\Entities\FeedbackDoc;

use Biffy\Entities\AbstractRepositoryInterface;

interface FeedbackDocRepositoryInterface extends AbstractRepositoryInterface
{
    public function getUnassignedFeedbackDocs();
}