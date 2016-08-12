<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupUserTable extends Migration {

    const TABLENAME = 'group_user';

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
			$table->integer('group_id')->unsigned()->index();
			$table->foreign('group_id')->references('id')->on(CreateGroupsTable::TABLENAME)->onDelete('cascade');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
            $table->boolean('is_store')->default(0);
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
                $table->dropForeign(['group_id']);
                $table->dropForeign(['user_id']);
            });
		Schema::drop(self::TABLENAME);
	}

}
