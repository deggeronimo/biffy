<?php

use Biffy\Entities\Group\Group;
use Biffy\Entities\Store\Store;
use Biffy\Entities\User\User;

class FakeGoogleImportSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        // create dev store + group
        $groupId = Group::create([
                'name' => 'Dev',
                'email' => 'group_dev@ubreakifix.com',
                'is_store' => true
            ])->id;

        Store::create([
                'name' => 'Dev',
                'group_id' => $groupId
            ]);

        // create test user and add to dev group
        $user = User::create([
                'given_name' => 'Test',
                'family_name' => 'User',
                'username' => 'test.test',
                'email' => 'test.test@ubreakifix.com',
                'picture_url' => '',
                'admin' => 1,
                'pin' => '1234'
            ]);

        $user->groups()->attach($groupId, ['is_store' => true]);
    }
} 