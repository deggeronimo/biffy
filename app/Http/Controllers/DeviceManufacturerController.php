<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\DeviceManufacturer\DeviceManufacturer;
use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\DeviceManufacturer\DeviceManufacturerService;
use Biffy\Services\Entities\Language\LanguageService;
use Biffy\Services\Entities\WebsiteFilter\WebsiteFilterService;

class DeviceManufacturerController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $languageService;
    protected $websiteFilterService;

    public function __construct(DeviceManufacturerService $service, LanguageService $languageService, WebsiteFilterService $websiteFilterService)
    {
        $this->service = $service;
        $this->languageService = $languageService;
        $this->websiteFilterService = $websiteFilterService;
    }

    protected function afterStore(DeviceManufacturer $result)
    {
        $websiteFilter = $this->websiteFilterService->create(
            [
                'filter_group_id' => 3,
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
