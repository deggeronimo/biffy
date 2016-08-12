<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardThreadViewsTable extends Migration {
    const TABLENAME = 'board_thread_views';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function (Blueprint $table) {
                $table->unsignedInteger('thread_id')->index();
                $table->foreign('thread_id')->references('id')->on(CreateBoardThreadsTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->boolean('current');
                $table->unique(['thread_id', 'user_id']);
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
                $table->dropForeign(['thread_id']);
                $table->dropForeign(['user_id']);
            });
        Schema::drop(self::TABLENAME);
	}

}

