<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\DeviceFamily\DeviceFamily;
use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\DeviceFamily\DeviceFamilyService;
use Biffy\Services\Entities\Language\LanguageService;
use Biffy\Services\Entities\WebsiteFilter\WebsiteFilterService;

class DeviceFamilyController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $languageService;
    protected $websiteFilterService;

    public function __construct(DeviceFamilyService $service, LanguageService $languageService, WebsiteFilterService $websiteFilterService)
    {
        $this->service = $service;
        $this->languageService = $languageService;
        $this->websiteFilterService = $websiteFilterService;
    }

    protected function afterStore(DeviceFamily $result)
    {
        $websiteFilter = $this->websiteFilterService->create(
            [
                'filter_group_id' => 5,
                'sort_order' => 0,
                'portal_filter_id' => $result->id
            ]
        );

        $result = $this->service->clearQuery()->clearFilters()->find($result->id);

        $languageList = $this->languageService->all();

        foreach ($languageList as $language)
        {
            LanguageTranslator::setWithoutKey($websiteFilter, $websiteFilter->id, 'name', $result->name, $language->id);
        }

        return $result;
    }
}