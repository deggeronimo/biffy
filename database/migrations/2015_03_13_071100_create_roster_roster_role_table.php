<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRosterRosterRoleTable extends Migration {

	const TABLENAME = 'roster_roster_role';

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
			$table->integer('roster_id')->unsigned()->index();
			$table->foreign('roster_id')->references('id')->on(CreateRostersTable::TABLENAME)->onDelete('cascade');
			$table->integer('roster_role_id')->unsigned()->index();
			$table->foreign('roster_role_id')->references('id')->on(CreateRosterRolesTable::TABLENAME)->onDelete('cascade');
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
