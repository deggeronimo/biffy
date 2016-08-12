<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreTaxesTable extends Migration {

    const TABLENAME = 'store_taxes';

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
            $table->string('name');
            $table->decimal('percentage', 7, 5);
            $table->boolean('active');
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
            });
		Schema::drop(self::TABLENAME);
	}

}
