<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardThreadSubscriptionsTable extends Migration {
    const TABLENAME = 'board_thread_subscriptions';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('thread_id')->index();
                $table->foreign('thread_id')->references('id')->on(CreateBoardThreadsTable::TABLENAME)->onDelete('cascade');
                $table->boolean('notify');
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
                $table->dropForeign(['user_id']);
                $table->dropForeign(['thread_id']);
            });
		Schema::drop(self::TABLENAME);
	}

}
