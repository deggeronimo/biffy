<?php namespace Biffy\Services\Entities\WebsiteFilterGroup;

use Biffy\Entities\WebsiteFilterGroup\WebsiteFilterGroupRepositoryInterface;
use Biffy\Services\Entities\LanguageKey\LanguageKeyService;
use Biffy\Services\Entities\Service;
use Biffy\Services\LanguageKeyCreator;

class WebsiteFilterGroupService extends Service
{
    use LanguageKeyCreator;

    public function __construct(WebsiteFilterGroupRepositoryInterface $repo, LanguageKeyService $languageKeyService)
    {
        $this->repo = $repo;

        $this->languageKeyService = $languageKeyService;
    }

    public function all()
    {
        $sorting = [ 'sort_order' => 'asc' ];

        $this->repo->sortBy($sorting);

        return $this->repo->get();
    }
}