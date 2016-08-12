<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreItemTable extends Migration {

    const TABLENAME = 'store_items';

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
			$table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on(CreateItemsTable::TABLENAME);
			$table->integer('store_id')->unsigned()->index();
            $table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME);
            $table->integer('stock')->default(0);
            $table->integer('on_order')->default(0);
            $table->integer('reserved')->default(0);
			$table->decimal('last_cost', 13, 2)->default('0.0');
			$table->decimal('unit_price', 13, 2)->default('0.0');
			$table->decimal('labor_cost', 13, 2)->default('0.0');
			$table->timestamps();

			$table->unique([ 'item_id', 'store_id' ]);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(self::TABLENAME);
	}

}
