<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRostersTable extends Migration {

    const TABLENAME = 'rosters';

    public function up()
    {
        Schema::create(self::TABLENAME, function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
            $table->unsignedInteger('employee_id')->index();
            $table->foreign('employee_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
            $table->dateTime('start_time')->index(); // UTC date time
            $table->unsignedInteger('time_interval'); // in Minutes
            $table->unsignedInteger('allowed_break')->nullable(); // in Minutes
//            Roster role is now pivot table to support multiple values
//            $table->unsignedInteger('roster_role_id')->index()->nullable();
//            $table->foreign('roster_role_id')->references('id')->on(CreateRosterRolesTable::TABLENAME);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop(self::TABLENAME);
    }

}
