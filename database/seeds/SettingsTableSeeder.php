<?php

use Biffy\Services\Entities\Setting\SettingService;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    protected $settings = [
        [
            'name' => 'Default Store',
            'key' => 'default-store',
            'type' => 'user-store-list',
            'extra' => null,
            'default' => null
        ]
    ];

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function run()
    {
        foreach ($this->settings as $setting) {
            $this->service->create($setting);
        }
    }
}