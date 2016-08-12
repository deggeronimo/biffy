<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupsTable extends Migration {

    const TABLENAME = 'groups';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
            $table->string('email')->unique();
            $table->boolean('is_store')->default(false);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(self::TABLENAME);
	}

}
