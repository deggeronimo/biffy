<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardThreadsTable extends Migration {
    const TABLENAME = 'board_threads';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->unsignedInteger('category_id')->index();
                $table->foreign('category_id')->references('id')->on(CreateBoardCategoriesTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->boolean('closed')->default(0);
                $table->boolean('sticky')->default(0);
                $table->integer('views');
                $table->timestamp('latest_post');
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
                $table->dropForeign(['category_id']);
                $table->dropForeign(['user_id']);
            });
		Schema::drop(self::TABLENAME);
	}

}
