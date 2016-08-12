<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeclockTable extends Migration {

    const TABLENAME = 'timeclock';

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
            $table->unsignedInteger('store_id')->index();
            $table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
            $table->unsignedInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
			$table->datetime('time_in')->index();
            $table->datetime('time_out')->index()->nullable();
            $table->tinyInteger('clock_type')->index();
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
                $table->dropForeign(['store_id']);
                $table->dropForeign(['user_id']);
            });
		Schema::drop(self::TABLENAME);
	}

}
