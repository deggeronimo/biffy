<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardPostsTable extends Migration {
    const TABLENAME = 'board_posts';

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
                $table->unsignedInteger('thread_id')->index()->nullable();
                $table->foreign('thread_id')->references('id')->on(CreateBoardThreadsTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('user_id')->index()->nullable();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->softDeletes();
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
                $table->dropForeign(['thread_id']);
                $table->dropForeign(['user_id']);
            });
        Schema::drop(self::TABLENAME);
	}

}
