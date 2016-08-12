<?php

use Biffy\Entities\DeviceRepair\DeviceRepair;
use Biffy\Entities\DeviceRepairType\DeviceRepairType;
use Biffy\Entities\DeviceType\DeviceType;
use Biffy\Entities\LanguageKey\LanguageKey;
use Biffy\Entities\LanguageKey\LanguageKeyRepositoryInterface;
use Biffy\Facades\LanguageTranslator;
use Illuminate\Database\Seeder;

class LanguageKeysTableSeeder extends Seeder
{
    protected $model;
    protected $keyDescriptorList;

    public function __construct(LanguageKey $model, DeviceRepair $deviceRepairModel, DeviceRepairType $deviceRepairTypeModel,
                                DeviceType $deviceTypeModel, LanguageKeyRepositoryInterface $repo)
    {
        $this->model = $model;
        $this->repo = $repo;

        $this->keyDescriptorList = [
//            [ 'model' => $deviceRepairModel, 'length' => 4, 'keys' => [ 'metaDescription', 'metaKeywords', 'webDescription' ] ],
//            [ 'model' => $deviceRepairTypeModel, 'length' => 4, 'keys' => [ 'name', 'metaDescription', 'metaKeywords', 'webDescription' ] ],
//            [ 'model' => $deviceTypeModel, 'length' => 66, 'keys' => [ 'metaDescription', 'metaKeywords', 'name', 'webDescription' ] ]
        ];
    }

    public function run()
    {
        foreach ($this->keyDescriptorList as $descriptor)
        {
            $model = $descriptor['model'];

            for ($id = 1; $id <= $descriptor['length']; $id ++)
            {
                foreach ($descriptor['keys'] as $key)
                {
                    $this->repo->create([
                        'key' => LanguageTranslator::generateKey($model, $id, $key)
                    ]);
                }
            }
        }
    }
}