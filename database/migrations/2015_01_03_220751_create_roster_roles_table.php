<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRosterRolesTable extends Migration {

    const TABLENAME = 'roster_roles';

    public function up()
    {
        Schema::create(self::TABLENAME, function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('category')->index();

            $table->unique(['category', 'name']);
        });
    }

    public function down()
    {
        Schema::drop(self::TABLENAME);
    }
}
