<?php

use Biffy\Entities\DeviceManufacturer\DeviceManufacturer;

class DeviceManufacturersTableSeeder extends AbstractArraySeeder
{
    public function __construct(DeviceManufacturer $model)
    {
        $this->model = $model;

        $oldManufacturers = DB::select(DB::raw("SELECT f.filter_id, fd.name FROM ubif_website_v2_dev.filter_description fd LEFT JOIN ubif_website_v2_dev.filter f ON f.filter_id = fd.filter_id LEFT JOIN ubif_website_v2_dev.filter_group fg  ON f.filter_group_id = fg.filter_group_id WHERE f.filter_group_id = '3' GROUP BY f.filter_id"));

        foreach ($oldManufacturers as $manufacturer)
        {
            //Get rid of a duplicate Google Manufacturer
            if ($manufacturer->filter_id == 39)
            {
                continue;
            }

            $this->itemList[] = [
                'id' => $manufacturer->filter_id,
                'name' => $manufacturer->name
            ];
        }
    }
}