<?php

use Biffy\Entities\DeviceRepair\DeviceRepair;
use Biffy\Entities\DeviceType\DeviceType;
use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\DeviceRepair\DeviceRepairService;
use Biffy\Services\Entities\Language\LanguageService;

class DeviceRepairsTableSeeder extends AbstractExistingTableSeeder
{
    protected $idFieldName = 'product_id';

    protected $joins = [
        'ubif_website_v2.product_to_category on ubif_website_v2.product_to_category.product_id = ubif_website_v2.product.product_id'
    ];

    protected $languageAttributeMaps = [
        'name' => 'name',
        'description' => 'web_description',
        'meta_description' => 'meta_description',
        'meta_keyword' => 'meta_keywords'
    ];

    protected $mappings = [
        'category_id' => 'device_type_id',
        'image' => 'image',
        'product_id' => 'id',
        'viewed' => 'view_count'
    ];

    protected $languageList;
    protected $languageService;

    protected $sourceTable = 'ubif_website_v2.product';

    protected $deviceTypeModel;

    private $parentIdFilterMap = [
        176 => 1,

        62 => 9,
        64 => 10,
        230 => 11,
        65 => 46,

        47 => 47,
        48 => 48,
        97 => 97,
        98 => 98,

        229 => 176,
        66 => 177
    ];

    private $currentPrice;

    public function __construct(DeviceRepairService $service, DeviceRepair $model, DeviceType $deviceTypeModel, LanguageService $languageService)
    {
        $this->service = $service;
        $this->languageService = $languageService;

        $this->model = $model;
        $this->deviceTypeModel = $deviceTypeModel;

        $this->languageList = $this->languageService->all();
    }

    public function afterInsert( & $newRow)
    {
        $productId = $newRow->id;

        $websiteCategoryDescriptions = DB::select(DB::raw("SELECT * FROM ubif_website_v2.product_description WHERE product_id = {$productId}"));

        foreach ($websiteCategoryDescriptions as $websiteCategoryDescription)
        {
            $languageId = $websiteCategoryDescription->language_id;

            foreach ($this->languageAttributeMaps as $from => $to)
            {
                $key = LanguageTranslator::generateKey($this->model, $newRow->id, $to);

                LanguageTranslator::set($key, $websiteCategoryDescription->$from, $languageId);
            }
        }

        foreach ($this->languageList as $language)
        {
            $key = LanguageTranslator::generateKey($this->model, $newRow->id, 'estimated_cost');

            LanguageTranslator::set($key, $this->currentPrice, $language->id);
        }

        return false;
    }

    public function beforeInsert( & $oldRow)
    {
        if ($oldRow->status == 0)
        {
            return null;
        }

        $result = DB::select(DB::raw("select * from ubif_website_v2.product_to_store where product_id = {$oldRow->product_id}"));

        if (count($result) == 0)
        {
            return null;
        }

        if (isset($this->parentIdFilterMap[$oldRow->category_id]))
        {
            $oldRow->category_id = $this->parentIdFilterMap[$oldRow->category_id];
        }

        $this->currentPrice = $oldRow->price;

        return $oldRow;
    }
}