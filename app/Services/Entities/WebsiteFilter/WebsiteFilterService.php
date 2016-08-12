<?php namespace Biffy\Services\Entities\WebsiteFilter;

use Biffy\Entities\WebsiteFilter\WebsiteFilterRepositoryInterface;
use Biffy\Services\Entities\LanguageKey\LanguageKeyService;
use Biffy\Services\Entities\Service;
use Biffy\Services\LanguageKeyCreator;

class WebsiteFilterService extends Service
{
    use LanguageKeyCreator;

    public function __construct(WebsiteFilterRepositoryInterface $repo, LanguageKeyService $languageKeyService)
    {
        $this->repo = $repo;

        $this->languageKeyService = $languageKeyService;
    }
}