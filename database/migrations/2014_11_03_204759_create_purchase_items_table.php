<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseItemsTable extends Migration {

    const TABLENAME = 'purchase_items';

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
            $table->integer('purchase_order_id')->unsigned()->index();
            $table->foreign('purchase_order_id')->references('id')->on(CreatePurchaseOrdersTable::TABLENAME)->onDelete('cascade');
            $table->integer('store_item_id')->unsigned()->index();
            $table->foreign('store_item_id')->references('id')->on(CreateStoreItemTable::TABLENAME)->onDelete('cascade');
            $table->integer('quantity');
			$table->decimal('cost', 13, 2)->default('0.0');
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
		Schema::drop(self::TABLENAME);
	}

}
