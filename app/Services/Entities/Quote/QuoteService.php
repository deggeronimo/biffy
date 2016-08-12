<?php namespace Biffy\Services\Entities\Quote;

use Biffy\Entities\Quote\QuoteRepositoryInterface;
use Biffy\Services\Entities\Service;

class QuoteService extends Service
{
    public function __construct(QuoteRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}