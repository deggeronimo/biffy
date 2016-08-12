<?php

use Biffy\Entities\DeviceType\DeviceType;
use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\DeviceType\DeviceTypeService;

class DeviceTypesTableSeeder extends AbstractExistingTableSeeder
{
    protected $idFieldName = 'category_id';

    protected $languageAttributeMaps = [
        'name' => 'name',
        'description' => 'web_description',
        'meta_description' => 'meta_description',
        'meta_keyword' => 'meta_keywords'
    ];

    protected $mappings = [
        'category_id' => 'id',
        'parent_id' => 'parent_device_type_id',
        'image' => 'image',
        'top' => 'top',
        'product' => 'product',
        'sort_order' => 'sort_order',
        'status' => 'status',
        'model' => 'model',
        'viewed' => 'view_count',
        'date_released' => 'release_date',
        'date_added' => 'created_at',
        'date_modified' => 'updated_at'
    ];

    protected $sourceTable = 'ubif_website_v2.category';

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

    public function __construct(DeviceType $model, DeviceTypeService $service)
    {
        $this->model = $model;

        $this->service = $service;
    }

    private $currentId;

    public function afterInsert( & $newRow)
    {
        $websiteCategoryDescriptions = DB::select(DB::raw("SELECT * FROM ubif_website_v2.category_description WHERE `category_id` = {$this->currentId}"));

        foreach ($websiteCategoryDescriptions as $websiteCategoryDescription)
        {
            $languageId = $websiteCategoryDescription->language_id;

            foreach ($this->languageAttributeMaps as $from => $to)
            {
                $key = LanguageTranslator::generateKey($this->model, $newRow->id, $to);

                LanguageTranslator::set($key, $websiteCategoryDescription->$from, $languageId);
            }
        }

        return false;
    }

    public function beforeInsert( & $oldRow)
    {
        if ($oldRow->status == 0)
        {
            return null;
        }

        $result = DB::select(DB::raw("select * from ubif_website_v2.category_to_store where `category_id` = {$oldRow->category_id}"));

        if (count($result) == 0)
        {
            return null;
        }

        $this->currentId = $oldRow->category_id;

        if (isset($this->parentIdFilterMap[$oldRow->category_id]))
        {
            $oldRow->category_id = $this->parentIdFilterMap[$oldRow->category_id];
        }

        if (isset($this->parentIdFilterMap[$oldRow->parent_id]))
        {
            $oldRow->parent_id = $this->parentIdFilterMap[$oldRow->parent_id];
        }

        return $oldRow;
    }

    public function complete()
    {
        $allRows = $this->model->with(['children'])->get();

        foreach ($allRows as $row)
        {
            $rowArray = $row->toArray();

            if (!is_null($rowArray['parent_device_type_id']))
            {
                if (count($rowArray['children']) == 0)
                {
                    $row->selectable = '1';
                    $row->save();
                }
            }
        }

        $manufacturerList = DB::select(DB::raw("SELECT c.category_id, cd.name, cf.filter_id, fd.name FROM ubif_website_v2.category c LEFT JOIN ubif_website_v2.category_description cd ON c.category_id = cd.category_id LEFT JOIN ubif_website_v2.category_filter cf ON c.category_id = cf.category_id LEFT JOIN ubif_website_v2.filter f ON cf.filter_id= f.filter_id LEFT JOIN ubif_website_v2.filter_description fd ON f.filter_id= fd.filter_id WHERE f.filter_group_id = '3' GROUP BY c.category_id"));

        foreach ($manufacturerList as $manufacturer)
        {
            $deviceType = $this->model->find($manufacturer->category_id);

            if (!$deviceType)
            {
                continue;
            }

            //Get rid of a duplicate Google Manufacturer
            if ($manufacturer->filter_id == 39)
            {
                $manufacturer->filter_id = 15;
            }

            $deviceType->device_manufacturer_id = $manufacturer->filter_id;
            $deviceType->save();
        }

        $familyList = DB::select(DB::raw("SELECT c.category_id, cd.name, cf.filter_id, fd.name FROM ubif_website_v2.category c LEFT JOIN ubif_website_v2.category_description cd ON c.category_id = cd.category_id LEFT JOIN ubif_website_v2.category_filter cf ON c.category_id = cf.category_id LEFT JOIN ubif_website_v2.filter f ON cf.filter_id= f.filter_id LEFT JOIN ubif_website_v2.filter_description fd ON f.filter_id= fd.filter_id WHERE f.filter_group_id = '5' GROUP BY c.category_id"));

        foreach ($familyList as $family)
        {
            $deviceType = $this->model->find($family->category_id);

            if (!$deviceType)
            {
                continue;
            }

            $deviceType->device_family_id = $family->filter_id;
            $deviceType->save();
        }
    }

    protected function orderBy()
    {
        return $this->sourceTable . '.category_id desc';
    }
}