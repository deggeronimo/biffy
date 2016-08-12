<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRepToPost extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(CreateBoardPostsTable::TABLENAME, function (Blueprint $table) {
                $table->integer('rep')->default(0);
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table(CreateBoardPostsTable::TABLENAME, function (Blueprint $table) {
                $table->dropColumn('rep');
            });
	}

}
