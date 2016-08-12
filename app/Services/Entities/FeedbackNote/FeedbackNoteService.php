<?php namespace Biffy\Services\Entities\FeedbackNote;

use Biffy\Entities\FeedbackNote\FeedbackNoteRepositoryInterface;
use Biffy\Services\Entities\Service;

class FeedbackNoteService extends Service
{
    public function __construct(FeedbackNoteRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}
