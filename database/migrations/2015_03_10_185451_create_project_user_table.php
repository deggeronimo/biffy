<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectUserTable extends Migration {
    const TABLENAME = 'project_user';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('project_id')->index();
                $table->foreign('project_id')->references('id')->on(CreateProjectsTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
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
                $table->dropForeign(['project_id']);
                $table->dropForeign(['user_id']);
            });
        Schema::drop(self::TABLENAME);
	}

}
