<?php

use Biffy\Entities\DeviceType\DeviceType;
use Biffy\Services\Entities\DeviceType\DeviceTypeService;

class WebsiteCategoryToDeviceTypeTableConverter extends \Illuminate\Database\Seeder
{
    protected $deviceTypeService;

    public function __construct(DeviceTypeService $deviceTypeService, DeviceType $deviceTypeModel)
    {
        $this->deviceTypeService = $deviceTypeService;

        $this->deviceTypeModel = $deviceTypeModel;
    }

    public function run()
    {
        return;

        $websiteCategoryList = DB::select(DB::raw('SELECT * FROM `website_category`'));

        foreach ($websiteCategoryList as $websiteCategory)
        {
            dd($websiteCategory);
            $categoryId = $websiteCategory->category_id;

            $websiteCategoryDescriptions = DB::select(DB::raw("SELECT * FROM `website_category_description` WHERE `category_id` = {$categoryId}"));

            dd($websiteCategoryDescriptions);
        }
    }
}