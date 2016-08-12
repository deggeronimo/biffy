<?php

use Biffy\Entities\DeviceFamily\DeviceFamily;

class DeviceFamiliesTableSeeder extends AbstractArraySeeder
{
    public function __construct(DeviceFamily $model)
    {
        $this->model = $model;

        $oldFamilies = DB::select(DB::raw("SELECT f.filter_id, fd.name FROM ubif_website_v2_dev.filter_description fd LEFT JOIN ubif_website_v2_dev.filter f ON f.filter_id = fd.filter_id LEFT JOIN ubif_website_v2_dev.filter_group fg ON f.filter_group_id = fg.filter_group_id WHERE f.filter_group_id = '5' GROUP BY f.filter_id"));

        foreach ($oldFamilies as $family)
        {
            $this->itemList[] = [
                'id' => $family->filter_id,
                'name' => $family->name
            ];
        }
    }
}