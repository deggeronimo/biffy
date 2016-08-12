<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionRoleTable extends Migration {

    const TABLENAME = 'permission_role';

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
			$table->integer('permission_id')->unsigned()->index();
			$table->foreign('permission_id')->references('id')->on(CreatePermissionsTable::TABLENAME)->onDelete('cascade');
			$table->integer('role_id')->unsigned()->index();
			$table->foreign('role_id')->references('id')->on(CreateRolesTable::TABLENAME)->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table(self::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['permission_id']);
                $table->dropForeign(['role_id']);
            });
		Schema::drop(self::TABLENAME);
	}

}
