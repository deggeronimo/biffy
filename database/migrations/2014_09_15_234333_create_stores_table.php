<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoresTable extends Migration {

    const TABLENAME = 'stores';
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
			$table->string('name')->unique();
            $table->integer('group_id')->unsigned()->index();
            $table->foreign('group_id')->references('id')->on(CreateGroupsTable::TABLENAME)->onDelete('cascade');
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
                $table->dropForeign(['group_id']);
            });
		Schema::drop(self::TABLENAME);
	}

}
