<?php

use Biffy\Services\Entities\Config\ConfigService;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    protected $config = [
        [
            'name' => 'Pay Period Start',
            'key' => 'pay-period-start',
            'type' => 'days-of-week',
            'extra' => '',
            'default' => 4
        ],
        [
            'name' => 'Appointments Per Day',
            'key' => 'appointments-per-day',
            'type' => 'input',
            'extra' => '',
            'default' => 4
        ],
        [
            'name' => 'Timezone',
            'key' => 'timezone',
            'type' => 'timezones',
            'default' => 'America/New_York'
        ],
        [
            'name' => 'Require Referral Source',
            'key' => 'require-referral-source',
            'type' => 'checkbox',
            'default' => 1
        ],
        [
            'name' => 'Average Repair Time',
            'key' => 'repair-time-average',
            'type' => 'input',
            'default' => 1
        ],
        [
            'name' => 'Repair Time Buffer',
            'key' => 'repair-time-buffer',
            'type' => 'input',
            'default' => 1.25
        ],
        [
            'name' => 'Split Part/Labor Cost',
            'key' => 'labor-split-cost',
            'type' => 'checkbox',
            'default' => 0
        ],
        [
            'name' => 'Tax Labor',
            'key' => 'labor-tax',
            'type' => 'checkbox',
            'default' => 0
        ],
        [
            'name' => 'Discount to require manager approval',
            'key' => 'discount-require-approval-percentage',
            'type' => 'input',
            'default' => 15
        ]
    ];

    public function __construct(ConfigService $service)
    {
        $this->service = $service;
    }

    public function run()
    {
        foreach ($this->config as $config) {
            $this->service->create($config);
        }
    }
}
