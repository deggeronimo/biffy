<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirstPostToThreads extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(CreateBoardThreadsTable::TABLENAME, function (Blueprint $table) {
                $table->unsignedInteger('first_post_id')->index()->nullable();
                $table->foreign('first_post_id')->references('id')->on(CreateBoardPostsTable::TABLENAME)->onDelete('cascade');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table(CreateBoardThreadsTable::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['first_post_id']);
            });
	}

}
