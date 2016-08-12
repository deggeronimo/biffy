<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTaskCommentsTable extends Migration {
    const TABLENAME = 'project_task_comments';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function (Blueprint $table) {
                $table->increments('id');
                $table->text('content');
                $table->unsignedInteger('task_id')->index()->nullable();
                $table->foreign('task_id')->references('id')->on(CreateProjectTasksTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
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
		Schema::table(self::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['task_id']);
                $table->dropForeign(['user_id']);
            });
        Schema::drop(self::TABLENAME);
	}

}
