<?php namespace Biffy\Services\Entities\DeviceRepairOptionItem;

use Biffy\Entities\DeviceRepairOptionItem\DeviceRepairOptionItemRepositoryInterface;
use Biffy\Services\Entities\LanguageKey\LanguageKeyService;
use Biffy\Services\Entities\Service;
use Biffy\Services\LanguageKeyCreator;

class DeviceRepairOptionItemService extends Service
{
    use LanguageKeyCreator;

    public function __construct(DeviceRepairOptionItemRepositoryInterface $repo, LanguageKeyService $languageKeyService)
    {
        $this->repo = $repo;

        $this->languageKeyService = $languageKeyService;
    }
}