<?php

use Biffy\Entities\WorkOrderStatus\WorkOrderStatus;

class WorkorderStatusesSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [
            'name' => 'Awaiting Repair',
            'initial' => true,
            'next_time' => 'queue',
            'action_text_key' => 'repair_completed_by'
        ],
        [
            'name' => 'Repair in Progress',
            'action_text_key' => 'repair_completed_by'
        ],
        [
            'name' => 'Awaiting Callback',
            'next_time' => '1|d',
            'action_text_key' => 'follow_up_by'
        ],
        [
            'name' => 'Awaiting Parts',
            'initial' => true,
            'next_time' => 'po',
            'action_text_key' => 'parts_arrive_by'
        ],
        [
            'name' => 'Unrepairable - RFP',
            'next_time' => '1|d',
            'action_text_key' => 'follow_up_by',
            'remove_items' => true
        ],
        [
            'name' => 'Repaired - RFP',
            'next_time' => '1|d',
            'action_text_key' => 'follow_up_by'
        ],
        [
            'name' => 'Need to Order Parts',
            'initial' => true,
            'next_time' => 'order',
            'action_text_key' => 'parts_ordered_by'
        ],
        [
            'name' => 'Awaiting Device',
            'next_time' => '1|d',
            'action_text_key' => 'follow_up_by'
        ],
        [
            'name' => 'Device Abandoned',
            'action_text_key' => 'device_disposed_by',
            'remove_items' => true
        ],
        [
            'name' => 'Awaiting Approval',
            'next_time' => '30|m',
            'action_text_key' => 'approved_by'
        ],
        [
            'name' => 'Declined - RFP',
            'next_time' => '1|d',
            'action_text_key' => 'follow_up_by',
            'remove_items' => true
        ],
        [
            'name' => 'Sale Completed',
            'user_can_set' => false
        ],
        [
            'name' => 'Awaiting Diag',
            'initial' => true,
            'next_time' => 'queue',
            'action_text_key' => 'diag_by'
        ]
    ];

    public function __construct(WorkOrderStatus $model)
    {
        $this->model = $model;
    }

    public function run()
    {
        parent::run();

        $actionTextStrings = [
            'repair_completed_by' => 'Repair will be completed by',
            'parts_arrive_by' => 'Parts should arrive by',
            'follow_up_by' => 'Follow up with customer by',
            'parts_ordered_by' => 'Parts will be ordered by',
            'approved_by' => 'Manager will approve device by',
            'diag_by' => 'Device will be diagnosed by',
            'device_disposed_by' => 'Device will be disposed by'
        ];

        $languageIds = \Biffy\Entities\Language\Language::lists('id');

        foreach ($actionTextStrings as $key => $string) {
            $keyId = \Biffy\Entities\LanguageKey\LanguageKey::create(['key' => 'workorder.status.action.' . $key])->id;
            foreach ($languageIds as $languageId) {
                \Biffy\Entities\LanguageString\LanguageString::create(['language_key_id' => $keyId, 'language_id' => $languageId, 'string' => $string]);
            }
        }
    }
}