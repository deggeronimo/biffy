<?php

class TestingSeeder extends BaseSeeder
{
    public function run()
    {
        $this->call('WorkorderStatusesSeeder');

//        $now = date('Y-m-d H:i:s');
//
//        DB::table('users')->insert([
//                'given_name' => '',
//                'family_name' => '',
//                'username' => 'test',
//                'email' => 'test.test@ubreakifix.com',
//                'picture_url' => '',
//                'admin' => 1,
//                'pin' => '1234',
//                'rep' => 0,
//                'created_at' => $now,
//                'updated_at' => $now
//            ]);
//
//        DB::table('groups')->insert([
//                'name' => 'Dev',
//                'email' => 'group_dev@ubreakifix.com',
//                'is_store' => 1,
//                'created_at' => $now,
//                'updated_at' => $now
//            ]);
//
//        DB::table('group_user')->insert([
//                'group_id' => 1,
//                'user_id' => 1,
//                'is_store' => 1
//            ]);
//
//        DB::table('stores')->insert([
//                'name' => 'Dev',
//                'group_id' => 1,
//                'created_at' => $now,
//                'updated_at' => $now
//            ]);
    }
} 