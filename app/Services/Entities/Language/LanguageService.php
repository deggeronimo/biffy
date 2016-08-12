<?php namespace Biffy\Services\Entities\Language;

use Biffy\Entities\Language\LanguageRepositoryInterface;
use Biffy\Services\Entities\Service;

class LanguageService extends Service
{
    public function __construct(LanguageRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}