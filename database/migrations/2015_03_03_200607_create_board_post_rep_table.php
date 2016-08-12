<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardPostRepTable extends Migration {
    const TABLENAME = 'board_post_rep';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function (Blueprint $table) {
                $table->unsignedInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('post_id')->index();
                $table->foreign('post_id')->references('id')->on(CreateBoardPostsTable::TABLENAME)->onDelete('cascade');
                $table->tinyInteger('rating');
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
                $table->dropForeign(['post_id']);
            });
        Schema::drop(self::TABLENAME);
	}

}
