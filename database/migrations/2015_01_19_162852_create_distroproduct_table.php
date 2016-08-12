<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDistroproductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('distroproduct', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id');
			$table->string('model');
			$table->string('sku');
			$table->string('upc');
			$table->string('ean');
			$table->string('jan');
			$table->string('isbn');
			$table->string('mpn');
			$table->string('location');
			$table->string('quantity');
			$table->string('stock_status_id');
			$table->string('image');
			$table->string('manufacturer_id');
			$table->string('shipping');
			$table->string('price');
			$table->string('cost');
			$table->string('points');
			$table->string('tax_class_id');
			$table->string('date_available');
			$table->string('weight');
			$table->string('weight_class_id');
			$table->string('length');
			$table->string('width');
			$table->string('height');
			$table->string('length_class_id');
			$table->string('subtract');
			$table->string('minimum');
			$table->string('sort_order');
			$table->string('status');
			$table->string('date_added');
			$table->string('date_modified');
			$table->string('viewed');
			$table->string('supplier_id');
			$table->string('reorder_point');
			$table->string('auto_minimum');
			$table->string('cost_amount');
			$table->string('cost_percentage');
			$table->string('cost_additional');
			$table->string('rma_avg_cost');
			$table->string('rma_quantity');
			$table->string('last_part_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('distroproduct');
	}

}
