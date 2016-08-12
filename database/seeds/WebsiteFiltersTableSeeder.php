<?php

use Biffy\Entities\WebsiteFilter\WebsiteFilter;
use Biffy\Services\Entities\Language\LanguageService;
use Biffy\Services\Entities\WebsiteFilter\WebsiteFilterService;

class WebsiteFiltersTableSeeder extends AbstractArraySeeder
{
    public function __construct(WebsiteFilterService $service, WebsiteFilter $model, LanguageService $languageService)
    {
        $this->service = $service;
        $this->languageService = $languageService;

        $this->model = $model;

        $languageList = $this->languageService->all();

        $websiteFilterList = DB::select(DB::raw("SELECT * FROM ubif_website_v2_dev.filter join ubif_website_v2_dev.filter_description on filter_description.filter_id = filter.filter_id where filter_description.language_id = 1"));

        foreach ($websiteFilterList as $websiteFilter)
        {
            //Get rid of a duplicate Google Manufacturer
            if ($websiteFilter->filter_id == 39)
            {
                continue;
            }

            $this->itemList[] = [
                'id' => $websiteFilter->filter_id,
                'filter_group_id' => $websiteFilter->filter_group_id,
                'sort_order' => $websiteFilter->sort_order,
                'portal_filter_id' => $websiteFilter->filter_id
            ];

            $nameStringList = DB::select(DB::raw("SELECT * FROM ubif_website_v2_dev.filter_description where filter_id = {$websiteFilter->filter_id}"));

            $nameList = [];

            foreach ($nameStringList as $nameString)
            {
                foreach ($languageList as $language)
                {
                    if ($language->id == $nameString->language_id)
                    {
                        $nameList[$language->lang] = $nameString->name;
                        break;
                    }
                }
            }

            $nameList['tt'] = $nameList['en'];

            $this->stringList[] = [
                'id' => $websiteFilter->filter_id,
                'values' => [
                    'name' => $nameList
                ]
            ];
        }
    }
}