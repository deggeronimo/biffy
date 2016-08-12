<?php

use Biffy\Entities\Group\Group;
use Biffy\Entities\User\User;
use Faker\Factory as Faker;

class GroupUserTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {

        $faker = Faker::create();
        $groups = Group::all();
        $user_ids = User::lists('id');

        foreach($groups as $group)
        {
            $ids = $faker->randomElements($user_ids, rand(2, 4));
	        if(! in_array(1, $ids)) $ids[] = 1; //Add user_id to all groups
	        $group->users()->sync($ids);
        }

    }
}