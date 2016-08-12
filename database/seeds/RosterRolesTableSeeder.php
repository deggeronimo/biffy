<?php

use Biffy\Entities\RosterRole\RosterRole;

class RosterRolesTableSeeder extends AbstractArraySeeder
{
    protected $itemList = [
        [ 'name' => 'FOH (Front of House)', 'category' => 'FOH' ],
        [ 'name' => 'PHN (Phone Overflow)', 'category' => 'FOH' ],

        [ 'name' => 'SDR (Same Day Repair)', 'category' => 'BOH' ],
        [ 'name' => 'MDR (Multi Day Repair)', 'category' => 'BOH' ],
        [ 'name' => 'WR (Warranty Repair)', 'category' => 'BOH' ],

        [ 'name' => 'ADM (Admin Day)', 'category' => 'Admin' ],
        [ 'name' => 'ADM-I (Inventory)', 'category' => 'Admin' ],
        [ 'name' => 'ADM-M (Marketing)', 'category' => 'Admin' ],
        [ 'name' => 'ADM-C (Cash Deposit)', 'category' => 'Admin' ],
        [ 'name' => 'ADM-S (Admin Scheduling)', 'category' => 'Admin' ],
        [ 'name' => 'ADM-O (Weekly Order)', 'category' => 'Admin' ],
        [ 'name' => 'ADM-WO (WO List Audit)', 'category' => 'Admin' ],
    ];

    public function __construct(RosterRole $model)
    {
        $this->model = $model;
    }
}