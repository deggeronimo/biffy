<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreConfigTable extends Migration {

    const TABLENAME = 'store_config';

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
            $table->unsignedInteger('config_id')->index();
            $table->foreign('config_id')->references('id')->on(CreateConfigTable::TABLENAME)->onDelete('cascade');
            $table->string('value')->nullable();
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
                $table->dropForeign(['config_id']);
            });

		Schema::drop(self::TABLENAME);
	}

}
